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

namespace Tests\Voucher\Redeem;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\WebTestCase as PiersTestCase;
use User\Entity\User;
use User\Repository\UserRepository;
use Voucher\Entity\Voucher;
use Voucher\Exception\UserHasAlreadyRedeemedThisVoucher;
use Voucher\Handler\VoucherHandlerInterface;
use Voucher\Redeem\Redeemer;
use Voucher\Repository\VoucherRepository;

class RedeemerTest extends KernelTestCase
{
    use PiersTestCase;

    /**
     * @expectedException \Voucher\Exception\RedeemExceptionInterface
     * @expectedExceptionMessage voucher.redeem.error.no_handler
     */
    public function test redeem with no handlers throws exception()
    {
        $redeemer = $this->createRedeemer([]);

        $redeemer->redeem($this->createVoucher(), $this->createUser());
    }

    public function test redeem with handler stopping propagation()
    {
        $handler = $this->createMock(VoucherHandlerInterface::class);

        $handler->expects($this->once())
            ->method('supports')
            ->willReturn(true)
        ;

        $voucher = $this->createVoucher();
        $user = $this->createUser();

        $handler->expects($this->once())
            ->method('handle')
            ->with($voucher, $user)
        ;

        $return = $this->createRedeemer([$handler])->redeem($voucher, $user);

        static::assertSame(1, $return);
    }

    public function test redeem impossible if voucher already used()
    {
        static::resetDatabase();

        static::bootKernel();

        $redeemer = static::$container->get(Redeemer::class);
        $user = static::$container->get(UserRepository::class)->findByUsernameOrEmail('lambda-user');
        $voucher = static::$container->get(VoucherRepository::class)->findByCode('ESTEREN_MAPS_3M');

        // We need to redeem it twice to trigger the exception in the second call.
        $result = $redeemer->redeem($voucher, $user);

        static::assertSame(4, $result);

        $this->expectException(UserHasAlreadyRedeemedThisVoucher::class);

        $redeemer->redeem($voucher, $user);
    }

    private function createVoucher(): Voucher
    {
        $voucher = $this->createMock(Voucher::class);
        $voucher
            ->method('getType')
            ->willReturn('test_type')
        ;

        return $voucher;
    }

    private function createUser(): User
    {
        $voucher = $this->createMock(User::class);
        $voucher
            ->method('getUsername')
            ->willReturn('test_user')
        ;

        return $voucher;
    }

    private function createRedeemer(array $handlers): Redeemer
    {
        return new Redeemer($handlers);
    }
}
