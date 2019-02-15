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
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use User\Entity\User;
use User\Security\FormLoginAuthenticator;

class LoginController extends AbstractController
{
    use TargetPathTrait;

    /**
     * @Route("/login", name="user_login", methods={"GET", "POST"})
     */
    public function loginAction(Request $request, Session $session)
    {
        if ($this->getUser() instanceof User) {
            return $this->redirect('/'.$request->getLocale().'/');
        }

        $redirectUrl = $request->query->get('redirect_url');

        if ($redirectUrl) {
            $this->saveTargetPath($request->getSession(), 'main', $redirectUrl);
        }

        $error = null;
        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif ($session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        }

        if ($error instanceof AuthenticationException) {
            $this->addFlash('error', $error->getMessage() ?: $error->getMessageKey());
        }

        return $this->render('user/Security/login.html.twig', [
            'last_username' => $session->get($lastUsernameKey),
            'csrf_token' => $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue(),
            'username_form_field' => FormLoginAuthenticator::USERNAME_OR_EMAIL_FORM_FIELD,
            'password_form_field' => FormLoginAuthenticator::PASSWORD_FORM_FIELD,
        ]);
    }
}
