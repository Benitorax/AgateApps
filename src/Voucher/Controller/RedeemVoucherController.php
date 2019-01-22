<?php

declare(strict_types=1);

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Voucher\Controller;

use Main\DependencyInjection\PublicService;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;
use Voucher\Exception\RedeemException;
use Voucher\Form\RedeemVoucherType;
use Voucher\Redeem\Redeemer;
use Voucher\Repository\VoucherRepository;

class RedeemVoucherController implements PublicService
{
    private $formFactory;
    private $twig;
    private $security;
    private $router;
    private $voucherRedeemer;
    private $voucherRepository;
    private $translator;

    public function __construct(
        FormFactoryInterface $formFactory,
        Environment $twig,
        Security $security,
        UrlGeneratorInterface $router,
        Redeemer $voucherRedeemer,
        VoucherRepository $voucherRepository,
        TranslatorInterface $translator
    ) {
        $this->formFactory = $formFactory;
        $this->twig = $twig;
        $this->security = $security;
        $this->router = $router;
        $this->voucherRedeemer = $voucherRedeemer;
        $this->voucherRepository = $voucherRepository;
        $this->translator = $translator;
    }

    /**
     * @Route("/voucher", name="redeem_voucher", methods={"GET", "POST"})
     */
    public function redeem(Request $request, Session $session)
    {
        $form = $this->formFactory->create(RedeemVoucherType::class);

        $form->handleRequest($request);

        $removeConfirmationButton = true;

        if ($form->isSubmitted() && $form->isValid()) {
            $code = $form->get('voucher_code')->getData();
            $voucher = $this->voucherRepository->findByCode($code);

            if (!$voucher) {
                $form->addError(new FormError($this->translator->trans('voucher.redeem.code_does_not_exist')));
            } elseif ($form->get('activate')->isClicked()) {
                $removeConfirmationButton = false;
            } elseif ($form->get('confirmation')->isClicked()) {
                try {
                    $this->voucherRedeemer->redeem($voucher, $this->security->getUser());

                    $session->getFlashBag()->add('success', 'voucher.redeem.success');

                    return new RedirectResponse($this->router->generate('redeem_voucher'));
                } catch (RedeemException $e) {
                    $form->addError(new FormError(
                        $this->translator->trans($e->redeemErrorMessage(), $e->getParameters()),
                        $e->redeemErrorMessage(),
                        $e->getParameters()
                    ));
                }
            }
        }

        return new Response($this->twig->render('voucher/redeem.html.twig', [
            'form' => $form->createView(),
            'remove_confirmation_button' => $removeConfirmationButton,
        ]));
    }
}
