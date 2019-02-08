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

namespace Tests\Voucher\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector;
use Tests\WebTestCase as PiersTestCase;
use User\Repository\UserRepository;
use Voucher\Data\VoucherType;
use Voucher\Entity\RedeemedVoucher;
use Voucher\Entity\Voucher;
use Voucher\Exception\ExceededNumberOfUsesForVoucher;
use Voucher\Exception\VoucherNotAvailable;
use Voucher\Redeem\Redeemer;
use Voucher\Repository\RedeemedVoucherRepository;
use Voucher\Repository\VoucherRepository;

class RedeemVoucherControllerTest extends WebTestCase
{
    use PiersTestCase;

    public function test redeem impossible if not logged in(): void
    {
        $client = $this->getClient('www.studio-agate.docker');

        $client->request('GET', '/fr/voucher');

        static::assertSame(401, $client->getResponse()->getStatusCode());
    }

    public function test redeem with correct voucher(): void
    {
        $client = $this->getClient('www.studio-agate.docker', ['debug' => true]);

        $this->login($client, 'www.studio-agate.docker', 'lambda-user', 'foobar');

        $voucherCode = 'ESTEREN_MAPS_3M';

        $client->request('GET', '/fr/voucher');

        $client->submitForm('Activer', [
            'redeem_voucher[voucher_code]' => $voucherCode,
        ]);

        $client->enableProfiler();
        $client->submitForm('Activer ce code');
        $profile = $client->getProfile();

        static::assertSame(302, $client->getResponse()->getStatusCode());
        static::assertTrue($client->getResponse()->isRedirect('/fr/voucher'));

        /** @var MessageDataCollector $emailCollector */
        $emailCollector = $profile->getCollector('swiftmailer');

        /** @var \Swift_Message[] $mails */
        $mails = $emailCollector->getMessages();

        static::assertCount(1, $mails);
        static::assertSame('Merci d\'avoir souscrit à l\'accès à Esteren Maps!', $mails[0]->getSubject());
        static::assertContains('Cette souscription vous donne l\'accès à toutes les fonctionnalités d\'Esteren Maps.', $mails[0]->getBody());

        $crawler = $client->followRedirect();

        $flashMessages = $crawler->filter('#flash-messages')->text();

        static::assertSame('Vous venez d\'utiliser un code d\'accès à Esteren Maps.
Votre souscription est effective dès maintenant, et
celle-ci sera effective pendant une durée de 90 jours.
Un e-mail de notification vous a été envoyé pour vous en informer.
Merci d\'avoir utilisé votre code !', \preg_replace('~\r?\n\s+~', "\n", \trim($flashMessages)));
    }

    public function test redeem impossible if voucher too much used(): void
    {
        static::bootKernel();

        $em = static::$container->get(EntityManagerInterface::class);
        $redeemer = static::$container->get(Redeemer::class);
        $voucher = Voucher::create(VoucherType::ESTEREN_MAPS, 'ESTEREN_MAPS_TEST', new \DateTimeImmutable(), null, 1);
        $redeemedVoucher = RedeemedVoucher::create($voucher, static::$container->get(UserRepository::class)->findByUsernameOrEmail('Pierstoval'));
        $em->persist($voucher);
        $em->persist($redeemedVoucher);
        $em->flush();

        $this->expectException(ExceededNumberOfUsesForVoucher::class);

        $redeemer->redeem($voucher, static::$container->get(UserRepository::class)->findByUsernameOrEmail('lambda-user'));
    }

    public function test redeem impossible if voucher not yet available(): void
    {
        static::bootKernel();

        $em = static::$container->get(EntityManagerInterface::class);
        $redeemer = static::$container->get(Redeemer::class);
        $voucher = Voucher::create(VoucherType::ESTEREN_MAPS, 'ESTEREN_MAPS_TEST', new \DateTimeImmutable('+1 day'));
        $em->persist($voucher);
        $em->flush();

        $this->expectException(VoucherNotAvailable::class);

        $redeemer->redeem($voucher, static::$container->get(UserRepository::class)->findByUsernameOrEmail('lambda-user'));
    }

    public function test redeem impossible if voucher not available anymore(): void
    {
        static::bootKernel();

        $em = static::$container->get(EntityManagerInterface::class);
        $redeemer = static::$container->get(Redeemer::class);
        $voucher = Voucher::create(VoucherType::ESTEREN_MAPS, 'ESTEREN_MAPS_TEST', new \DateTimeImmutable('-7 days'), new \DateTimeImmutable('-3 days'));
        $em->persist($voucher);
        $em->flush();

        $this->expectException(VoucherNotAvailable::class);

        $redeemer->redeem($voucher, static::$container->get(UserRepository::class)->findByUsernameOrEmail('lambda-user'));
    }

    public function test valid redeem for esteren maps voucher(): void
    {
        static::bootKernel();

        $redeemer = static::$container->get(Redeemer::class);
        $voucher = static::$container->get(VoucherRepository::class)->findByCode('ESTEREN_MAPS_3M');
        $user = static::$container->get(UserRepository::class)->findByUsernameOrEmail('lambda-user');

        $redeemDate = new \DateTimeImmutable();
        $result = $redeemer->redeem($voucher, $user);

        static::assertSame(4, $result);

        $redeemed = static::$container->get(RedeemedVoucherRepository::class)->findByVoucherAndUser($voucher, $user);

        static::assertCount(1, $redeemed);
        static::assertSame($voucher, $redeemed[0]->getVoucher());
        static::assertSame($user, $redeemed[0]->getUser());
        static::assertSame($redeemed[0]->getRedeemedAt()->format('Y-m-d H:i:s'), $redeemDate->format('Y-m-d H:i:s'));
    }

    public function login(Client $client, string $host, string $username, string $password): void
    {
        $crawler = $client->request('GET', "http://$host/fr/login");

        $formNode = $crawler->filter('#form_login');
        static::assertNotEmpty($formNode, "Could not retrieve login form.\n".$crawler->html());

        $form = $formNode->form();
        $form->get('_username_or_email')->setValue($username);
        $form->get('_password')->setValue($password);

        $client->submit($form);
    }
}
