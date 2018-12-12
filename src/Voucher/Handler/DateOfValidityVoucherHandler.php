<?php

namespace Voucher\Handler;

use User\Entity\User;
use Voucher\Entity\Voucher;
use Voucher\Exception\VoucherNotAvailable;

class DateOfValidityVoucherHandler implements VoucherHandlerInterface
{
    public static function getPriority(): int
    {
        return 100;
    }

    public function supports(Voucher $voucher, User $user): bool
    {
        return true;
    }

    public function handle(Voucher $voucher, User $user): void
    {
        $now = new \DateTimeImmutable();

        if (
            ($voucher->getValidFrom() && $now < $voucher->getValidFrom())
            ||
            ($voucher->getValidUntil() && $now > $voucher->getValidUntil())
        ) {
            throw new VoucherNotAvailable($voucher, $user);
        }
    }
}