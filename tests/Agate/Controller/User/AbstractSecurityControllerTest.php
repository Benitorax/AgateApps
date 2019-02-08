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

namespace Tests\Agate\Controller\User;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Form;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Tests\WebTestCase as PiersTestCase;
use User\Entity\User;
use User\Repository\UserRepository;

abstract class AbstractSecurityControllerTest extends WebTestCase
{
    use PiersTestCase;

    abstract protected function getLocale(): string;

    public function testForbiddenAdmin(): void
    {
        $locale = $this->getLocale();

        $client = $this->getClient('back.esteren.docker', [], 'ROLE_USER');

        $client->request('GET', "/$locale/");

        static::assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testAllowedAdmin(): void
    {
        $locale = $this->getLocale();

        $client = $this->getClient('back.esteren.docker', [], 'ROLE_ADMIN');

        $crawler = $client->request('GET', "/$locale/");

        static::assertSame(200, $client->getResponse()->getStatusCode(), $crawler->filter('title')->html());
        static::assertSame('EasyAdmin', $crawler->filter('meta[name="generator"]')->attr('content'));
        static::assertSame('', \trim($crawler->filter('#main')->text()));
    }

    public function testRegisterAndLoginWithoutConfirmingEmail(): void
    {
        $locale = $this->getLocale();

        $user = new User();
        $user
            ->setUsername("test_user_confirm_$locale")
            ->setUsernameCanonical("test_user_confirm_$locale")
            ->setEmail("testconfirm$locale@local.to")
            ->setEmailCanonical("testconfirm$locale@local.to")
        ;

        $client = $this->getClient('corahnrin.esteren.docker');

        $hashed = self::$container->get(UserPasswordEncoderInterface::class)->encodePassword($user, 'whatever');
        $user->setPassword($hashed);

        $em = self::$container->get(EntityManagerInterface::class);
        $em->persist($user);
        $em->flush();

        $client->request('POST', "/$locale/login_check", [
            '_username_or_email' => "test_user_confirm_$locale",
            '_password' => 'whatever',
        ]);

        static::assertSame(302, $client->getResponse()->getStatusCode());
        static::assertTrue($client->getResponse()->isRedirect("/$locale/login"));
        $crawler = $client->followRedirect();

        // Once redirected, we check the flash messages are correct
        $flashPasswordChanged = self::$container->get(TranslatorInterface::class)->trans('security.email_not_confirmed');
        static::assertContains($flashPasswordChanged, $crawler->filter('#layout #flash-messages')->html());
    }

    public function testRegister(): void
    {
        $locale = $this->getLocale();

        $client = $this->getClient('corahnrin.esteren.docker');

        $crawler = $client->request('GET', "/$locale/register");

        $formNode = $crawler->filter('form.user_registration_register');

        static::assertEquals(1, $formNode->count(), 'Form wasn\'t found in the request');

        /** @var Form $form */
        $form = $formNode->form();

        // Fill registration form
        $form['registration_form[username]'] = 'test_user';
        $form['registration_form[email]'] = 'test_user@local.docker';
        $form['registration_form[plainPassword]'] = 'fakePassword';
        $form['registration_form[optin]'] = true;

        // Submit form
        $crawler = $client->submit($form);
        $response = $client->getResponse();

        // Check redirection was made correctly to the Profile page
        static::assertTrue($response->isRedirect("/$locale/login"), 'Does not redirect to login page. Maybe form values are incorrect?');

        $crawler->clear();
        $crawler = $client->followRedirect();

        // Check flash messages are correct
        $flashUserCreated = self::$container->get(TranslatorInterface::class)->trans('registration.confirmed', ['%username%' => 'test_user'], 'user');
        static::assertContains($flashUserCreated, $crawler->filter('#layout #flash-messages')->html());

        $crawler->clear();
    }

    public function testConfirmEmail(): void
    {
        $locale = $this->getLocale();

        $client = $this->getClient('corahnrin.esteren.docker');

        $user = User::create('test_user', 'test@local.host', '');
        $user->setConfirmationToken('test_confirmation_token');
        $em = self::$container->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        static::assertNotNull(self::$container->get(UserRepository::class)->findOneBy(['username' => 'test_user']));

        $client->request('GET', "/$locale/register/confirm/".$user->getConfirmationToken());

        static::assertSame(302, $client->getResponse()->getStatusCode());
        static::assertTrue($client->getResponse()->isRedirect("/$locale"));
        static::assertNull($user->getConfirmationToken());
        static::assertSame(
            [self::$container->get(TranslatorInterface::class)->trans('registration.confirmed', ['%username%' => $user->getUsername()], 'user')],
            self::$container->get(SessionInterface::class)->getFlashBag()->get('success')
        );
    }

    public function testLogin(): void
    {
        $locale = $this->getLocale();

        $client = $this->getClient('corahnrin.esteren.docker');

        /** @var EncoderFactoryInterface $encoderFactory */
        $encoderFactory = self::$container->get(EncoderFactoryInterface::class);
        $user = User::create('test_user', 'test@local.host', $encoderFactory->getEncoder(User::class)->encodePassword('fakePassword', ''));
        $user->setEmailConfirmed(true);
        $em = self::$container->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        $crawler = $client->request('GET', "/$locale/login");

        $formNode = $crawler->filter('form#form_login');

        static::assertEquals(1, $formNode->count(), 'Form wasn\'t found in the request');

        /** @var Form $form */
        $form = $formNode->form();

        // Fill registration form
        $form['_username_or_email'] = 'test_user';
        $form['_password'] = 'fakePassword';

        // Submit form
        $crawler = $client->submit($form);

        $response = $client->getResponse();

        // Check redirection was made correctly to the Profile page
        static::assertTrue($response->isRedirect("/$locale"), 'Successful login does not redirect well. Redirects to: '.($response->headers->get('Location') ?: 'No redirection'));

        $crawler->clear();
        $client->followRedirects(true);
        $crawler = $client->followRedirect();

        // Check user is authenticated

        static::assertCount(2, $crawler->filter('.logout_link'));

        $crawler->clear();
    }

    public function testChangePassword(): void
    {
        $locale = $this->getLocale();

        $client = $this->getClient('corahnrin.esteren.docker');

        $encoderFactory = self::$container->get(EncoderFactoryInterface::class);
        $user = User::create('test_user', 'test@local.host', $encoderFactory->getEncoder(User::class)->encodePassword('fakePassword', ''));
        $user->setEmailConfirmed(true);
        $em = self::$container->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        $user = self::$container->get(UserRepository::class)->loadUserByUsername('test_user');
        static::assertNotNull($user);
        static::setToken($client, $user, $user->getRoles());

        $crawler = $client->request('GET', "/$locale/profile/change-password");

        // Fill "change password" form
        $form = $crawler->filter('form.user_change_password')->form();

        $form['change_password_form[current_password]'] = 'fakePassword';
        $form['change_password_form[plainPassword][first]'] = 'newPassword';
        $form['change_password_form[plainPassword][second]'] = 'newPassword';

        $client->submit($form);

        // Check that it redirects to profile page
        static::assertSame(302, $client->getResponse()->getStatusCode());
        static::assertTrue($client->getResponse()->isRedirect("/$locale/profile"));

        $crawler->clear();
        $crawler = $client->followRedirect();

        // Once redirected, we check the flash messages are correct
        $flashPasswordChanged = self::$container->get(TranslatorInterface::class)->trans('change_password.flash.success', [], 'user');
        static::assertContains($flashPasswordChanged, $crawler->filter('#layout #flash-messages')->html());

        // Now check that new password is correctly saved in database
        $user = self::$container->get(UserRepository::class)->loadUserByUsername('test_user');
        /** @var PasswordEncoderInterface $encoder */
        $encoder = self::$container->get(EncoderFactoryInterface::class)->getEncoder($user);
        static::assertTrue($encoder->isPasswordValid($user->getPassword(), 'newPassword', $user->getSalt()));

        $crawler->clear();
    }

    public function testEditProfile(): void
    {
        $locale = $this->getLocale();

        $client = $this->getClient('corahnrin.esteren.docker');

        $encoderFactory = self::$container->get(EncoderFactoryInterface::class);
        $user = User::create('test_user', 'test@local.host', $encoderFactory->getEncoder(User::class)->encodePassword('fakePassword', ''));
        $user->setEmailConfirmed(true);
        $em = self::$container->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        /** @var UserRepository $userRepository */
        $userRepository = self::$container->get(UserRepository::class);
        /** @var User $user */
        $user = $userRepository->loadUserByUsername('test_user');
        static::setToken($client, $user, $user->getRoles());

        $crawler = $client->request('GET', "/$locale/profile");

        // Fill the "edit profile" form
        $form = $crawler->filter('form.user_profile_edit')->form();

        $form['profile_form[username]'] = 'another_test_username';
        $form['profile_form[email]'] = $user->getEmail();
        $form['profile_form[currentPassword]'] = 'fakePassword';

        $client->submit($form);

        // Check that form submission redirects to same page
        static::assertSame(302, $client->getResponse()->getStatusCode());
        static::assertTrue($client->getResponse()->isRedirect("/$locale/profile#edit_profile"));
        $crawler->clear();
        $crawler = $client->followRedirect();

        // Once redirected, we check the flash messages are correct
        $flashPasswordChanged = self::$container->get(TranslatorInterface::class)->trans('profile.flash.updated', [], 'user');
        static::assertContains($flashPasswordChanged, $crawler->filter('#layout #flash-messages')->html());

        $crawler->clear();
    }

    public function testResetPasswordRequest(): void
    {
        $locale = $this->getLocale();

        $client = $this->getClient('corahnrin.esteren.docker');

        $encoderFactory = self::$container->get(EncoderFactoryInterface::class);
        $user = User::create('test_user', 'test@local.host', $encoderFactory->getEncoder(User::class)->encodePassword('fakePassword', ''));
        $user->setEmailConfirmed(true);
        $em = self::$container->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        /** @var UserRepository $userRepository */
        $userRepository = self::$container->get(UserRepository::class);
        /** @var User $user */
        $user = $userRepository->loadUserByUsername('test_user');

        static::setToken($client, $user, $user->getRoles());

        $crawler = $client->request('GET', "/$locale/resetting/request");

        $form = $crawler->filter('form.user_resetting_request')->form();

        $form['username'] = 'test_user';
        $client->submit($form);
        static::assertSame(302, $client->getResponse()->getStatusCode());
        $crawler->clear();
        do {
            $crawler = $client->followRedirect();
        } while (302 === $client->getResponse()->getStatusCode());

        // This message contains informations about user resetting token TTL.
        // This information is set in the User ResettingController and must be copied here just for testing.
        $emailSentMessage = self::$container->get(TranslatorInterface::class)->trans('resetting.check_email', [], 'user');
        $crawlerContent = \trim($crawler->filter('#content')->html());
        static::assertContains($emailSentMessage, $crawlerContent);

        $crawler->clear();
        $crawler = $client->request('GET', "/$locale/resetting/reset/".$user->getConfirmationToken());

        $form = $crawler->filter('form.user_resetting_reset')->form();

        $form['resetting_form[plainPassword]'] = 'anotherNewPassword';

        $client->submit($form);

        static::assertTrue($client->getResponse()->isRedirect("/$locale/login"));
        $crawler->clear();
        do {
            $crawler = $client->followRedirect();
        } while (302 === $client->getResponse()->getStatusCode());
        $flashNode = $crawler->filter('.card-panel.success');
        static::assertSame(1, $flashNode->count());

        $resettingSuccessMessage = self::$container->get(TranslatorInterface::class)->trans('resetting.flash.success', [], 'user');
        $crawlerContent = \trim($flashNode->html());
        static::assertContains($resettingSuccessMessage, $crawlerContent);

        $crawler->clear();
    }
}
