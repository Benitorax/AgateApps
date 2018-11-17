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
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\WebTestCase as PiersTestCase;
use User\Repository\UserRepository;
use Voucher\Entity\RedeemedVoucher;
use Voucher\Entity\Voucher;
use Voucher\Exception\ExceededNumberOfUsesForVoucher;
use Voucher\Exception\UserHasAlreadyRedeemedThisVoucher;
use Voucher\Exception\VoucherNotAvailable;
use Voucher\Redeem\Redeemer;
use Voucher\Repository\VoucherRepository;
use Voucher\VoucherType;

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

        static::bootKernel();

        $code = 'ESTEREN_MAPS_3M';

        $redeemer = static::$container->get(Redeemer::class);
        $user = static::$container->get(UserRepository::class)->findByUsernameOrEmail('lambda-user');
        $voucher = static::$container->get(VoucherRepository::class)->findByCode($code);

        // We need to redeem it twice to trigger the exception in the second call.
        $result = $redeemer->redeem($voucher, $user);

        static::assertSame(4, $result);

        $this->expectException(UserHasAlreadyRedeemedThisVoucher::class);

        $redeemer->redeem($voucher, $user);
    }

    public function test redeem impossible if voucher too much used()
    {
        static::resetDatabase();

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

    public function test redeem impossible if voucher not yet available()
    {
        static::resetDatabase();

        static::bootKernel();

        $em = static::$container->get(EntityManagerInterface::class);
        $redeemer = static::$container->get(Redeemer::class);
        $voucher = Voucher::create(VoucherType::ESTEREN_MAPS, 'ESTEREN_MAPS_TEST', new \DateTimeImmutable('+1 day'));
        $em->persist($voucher);
        $em->flush();

        $this->expectException(VoucherNotAvailable::class);

        $redeemer->redeem($voucher, static::$container->get(UserRepository::class)->findByUsernameOrEmail('lambda-user'));
    }

    public function test redeem impossible if voucher not available anymore()
    {
        static::resetDatabase();

        static::bootKernel();

        $em = static::$container->get(EntityManagerInterface::class);
        $redeemer = static::$container->get(Redeemer::class);
        $voucher = Voucher::create(VoucherType::ESTEREN_MAPS, 'ESTEREN_MAPS_TEST', new \DateTimeImmutable('-7 days'), new \DateTimeImmutable('-3 days'));
        $em->persist($voucher);
        $em->flush();

        $this->expectException(VoucherNotAvailable::class);

        $redeemer->redeem($voucher, static::$container->get(UserRepository::class)->findByUsernameOrEmail('lambda-user'));
    }
}
