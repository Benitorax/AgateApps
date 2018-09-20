<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace User\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use User\Entity\User;
use User\Repository\UserRepository;

final class FormLoginAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    public const USERNAME_OR_EMAIL_FORM_FIELD = '_username_or_email';
    public const PASSWORD_FORM_FIELD = '_password';

    private const PROVIDER_KEY = 'main'; // Firewall name

    private const LOGIN_ROUTE = 'user_login';

    private const NO_REFERER_ROUTES = [
        self::LOGIN_ROUTE,
        'user_login_check',
        'user_register',
        'user_logout',
        'user_check_email',
        'user_registration_confirm',
        'user_registration_confirmed',
        'user_resetting_request',
        'user_resetting_send_email',
        'user_resetting_check_email',
        'user_resetting_reset',
        'user_change_password',
    ];

    private $router;
    private $encoder;
    private $defaultLocale;

    public function __construct(RouterInterface $router, UserPasswordEncoderInterface $encoder, string $defaultLocale)
    {
        $this->router = $router;
        $this->encoder = $encoder;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        if ($request->hasSession()) {
            if (\in_array($request->attributes->get('_route'), static::NO_REFERER_ROUTES, true)) {
                $this->removeTargetPath($request->getSession(), static::PROVIDER_KEY);
            } else {
                $this->saveTargetPath($request->getSession(), static::PROVIDER_KEY, $request->getUri());
            }
        }

        return parent::start($request, $authException);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        return
            $request->isMethod('POST')
            && $request->request->has(self::USERNAME_OR_EMAIL_FORM_FIELD)
            && $request->request->has(self::PASSWORD_FORM_FIELD)
            && $request->getPathInfo() === $this->router->generate('user_login_check')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function getLoginUrl()
    {
        return $this->router->generate(static::LOGIN_ROUTE);
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        $usernameOrEmail = $request->request->get(self::USERNAME_OR_EMAIL_FORM_FIELD);
        $request->getSession()->set(Security::LAST_USERNAME, $usernameOrEmail);
        $password = $request->request->get(self::PASSWORD_FORM_FIELD);

        return UsernamePasswordCredentials::create(
            $usernameOrEmail,
            $password
        );
    }

    /**
     * {@inheritdoc}
     *
     * @param UsernamePasswordCredentials $credentials
     * @param UserRepository              $userProvider
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $user = $userProvider->loadUserByUsername($credentials->getUsernameOrEmail());

        if (!$user) {
            throw new AuthenticationException('security.bad_credentials');
        }

        if ($user && !$user->isEmailConfirmed()) {
            throw new AuthenticationException('security.email_not_confirmed');
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     *
     * @param UsernamePasswordCredentials $credentials
     * @param UserInterface|User          $user
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!$this->encoder->isPasswordValid($user, $credentials->getPassword())) {
            throw new BadCredentialsException('security.bad_credentials');
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $defaultUrl = \rtrim($this->router->generate('root', ['_locale' => $request->getLocale() ?: $this->defaultLocale]), '/').'/';

        $session = $request->getSession();

        $targetPath = $this->getTargetPath($session, $providerKey) ?: $defaultUrl;

        // Make sure username is not stored for next login
        $session->remove(Security::LAST_USERNAME);

        return new RedirectResponse($targetPath);
    }
}
