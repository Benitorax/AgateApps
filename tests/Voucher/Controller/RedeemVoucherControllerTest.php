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

namespace Tests\Voucher\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\WebTestCase as PiersTestCase;
use User\Repository\UserRepository;
use Voucher\Entity\RedeemedVoucher;
use Voucher\Repository\VoucherRepository;

class RedeemVoucherControllerTest extends WebTestCase
{
    use PiersTestCase;

    public function test redeem impossible if not logged in()
    {
        $client = $this->getClient('www.studio-agate.docker');

        $client->request('GET', '/fr/voucher');

        static::assertSame(401, $client->getResponse()->getStatusCode());
    }

    public function test redeem impossible if voucher already used()
    {
        static::resetDatabase();

        $client = $this->getClient('www.studio-agate.docker');

        $code = 'ESTEREN_MAPS_3M';

        $user = static::$container->get(UserRepository::class)->findByUsernameOrEmail('Pierstoval');
        $voucher = static::$container->get(VoucherRepository::class)->findByCode($code);

        static::setToken($client, $user);

        $redeemed = RedeemedVoucher::create($voucher, $user);

        $em = static::$container->get(EntityManagerInterface::class);
        $em->persist($redeemed);
        $em->flush();

        $crawler = $client->request('GET', '/fr/voucher');

        $this->submitAndConfirmVoucher($client, $crawler, $code);

        static::assertSame(302, $client->getResponse()->getStatusCode());
        static::assertTrue($client->getResponse()->isRedirect('/fr/voucher'));
    }

    private function submitAndConfirmVoucher(Client $client, Crawler $crawler, string $code)
    {
        $form = $crawler->selectButton('Activer')->form();

        $crawler = $client->submit($form, [
            'redeem_voucher[voucher_code]' => $code,
        ]);

        static::assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Activer ce code')->form();

        return $client->submit($form);
    }
}
