<?php

declare(strict_types=1);

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Esteren\Controller;

use Agate\Exception\PortalElementNotFound;
use Agate\Repository\PortalElementRepository;
use Main\DependencyInjection\PublicService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController implements PublicService
{
    private $portalElementRepository;
    private $twig;

    public function __construct(PortalElementRepository $portalElementRepository, Environment $twig)
    {
        $this->portalElementRepository = $portalElementRepository;
        $this->twig = $twig;
    }

    /**
     * @Route("/", name="esteren_portal_home", methods={"GET"})
     */
    public function indexAction(string $_locale): Response
    {
        $portalElement = $this->portalElementRepository->findForHomepage($_locale, 'esteren');

        if (!$portalElement) {
            throw new PortalElementNotFound('esteren', $_locale);
        }

        $template = 'esteren/index-'.$_locale.'.html.twig';

        return new Response($this->twig->render($template, [
            'portal_element' => $portalElement,
        ]));
    }
}
