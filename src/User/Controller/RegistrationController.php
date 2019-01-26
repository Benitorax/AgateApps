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

namespace User\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Translation\TranslatorInterface;
use User\Entity\User;
use User\Form\Type\RegistrationFormType;
use User\Mailer\UserMailer;
use User\Repository\UserRepository;
use User\Util\TokenGeneratorTrait;

class RegistrationController extends AbstractController
{
    use TokenGeneratorTrait;

    private $passwordEncoder;
    private $mailer;
    private $userRepository;
    private $translator;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        UserMailer $mailer,
        UserRepository $userRepository,
        TranslatorInterface $translator
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
        $this->translator = $translator;
    }

    /**
     * @Route("/register", name="user_register", methods={"GET", "POST"})
     */
    public function registerAction(Request $request, Session $session): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('user_profile_edit');
        }

        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
            $user->setConfirmationToken($this->generateToken());
            $user->setEmailConfirmed(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->mailer->sendRegistrationEmail($user);

            $this->addFlash('success', $this->translator->trans('registration.confirmed', ['%username%' => $user->getUsername()], 'user'));

            $session->set(Security::LAST_USERNAME, $user->getUsername());

            return $this->redirectToRoute('user_login');
        }

        return $this->render('user/Registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/check-email", name="user_check_email", methods={"GET"})
     */
    public function checkEmailAction(Session $session)
    {
        $email = $session->get('user_send_confirmation_email/email');

        if (empty($email)) {
            return $this->redirectToRoute('user_register');
        }

        $session->remove('user_send_confirmation_email/email');
        $user = $this->userRepository->findOneByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(\sprintf('The user with email "%s" does not exist', $email));
        }

        return $this->render('user/Registration/check_email.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/register/confirm/{token}", name="user_registration_confirm", requirements={"token" = ".+"}, methods={"GET"})
     */
    public function confirmAction(string $token, string $_locale)
    {
        /** @var \User\Entity\User|null $user */
        $user = $this->userRepository->findOneBy(['confirmationToken' => $token]);

        if (null === $user) {
            throw new NotFoundHttpException(\sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        $user->setConfirmationToken(null);
        $user->setEmailConfirmed(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash('success', $this->translator->trans('registration.confirmed', ['%username%' => $user->getUsername()], 'user'));

        return $this->redirect('/'.$_locale);
    }
}
