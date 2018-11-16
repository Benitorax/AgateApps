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

use PHPUnit\Framework\TestCase;
use User\Entity\User;
use Voucher\Entity\Voucher;
use Voucher\Exception\StopRedeemPropagation;
use Voucher\Handler\VoucherHandlerInterface;
use Voucher\Redeem\Redeemer;

class RedeemerTest extends TestCase
{
    /**
     * @expectedException \Voucher\Exception\RedeemExceptionInterface
     * @expectedExceptionMessage No handler for voucher of type "test_type" and for user "test_user".
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
            ->willReturn(true);

        $handler->expects($this->once())
            ->method('handle')
            ->willThrowException(new StopRedeemPropagation());

        $return = $this->createRedeemer([$handler])->redeem($this->createVoucher(), $this->createUser());

        static::assertSame(1, $return);
    }

    private function createVoucher(): Voucher
    {
        $voucher = $this->createMock(Voucher::class);
        $voucher->expects($this->any())
            ->method('getType')
            ->willReturn('test_type');

        return $voucher;
    }

    private function createUser(): User
    {
        $voucher = $this->createMock(User::class);
        $voucher->expects($this->any())
            ->method('getUsername')
            ->willReturn('test_user');

        return $voucher;
    }

    private function createRedeemer(array $handlers): Redeemer
    {
        return new Redeemer($handlers);
    }
}
