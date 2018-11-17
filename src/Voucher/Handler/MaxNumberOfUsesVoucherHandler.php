<?php

namespace Voucher\Handler;

use Voucher\Entity\Voucher;
use Voucher\Exception\ExceededNumberOfUsesForVoucher;
use Voucher\Repository\RedeemedVoucherRepository;
use User\Entity\User;

class MaxNumberOfUsesVoucherHandler implements VoucherHandlerInterface
{
    private $redeemedVoucherRepository;

    public function __construct(RedeemedVoucherRepository $redeemedVoucherRepository) {
        $this->redeemedVoucherRepository = $redeemedVoucherRepository;
    }

    public static function getPriority(): int
    {
        return 90;
    }

    public function supports(Voucher $voucher, User $user): bool
    {
        return true;
    }

    public function handle(Voucher $voucher, User $user): void
    {
        $maxNumberOfUses = $voucher->getMaxNumberOfUses();

        if ($maxNumberOfUses > 0) {
            $used = $this->redeemedVoucherRepository->getNumberOfVouchersUsedForType($voucher->getType());

            if ($used >= $maxNumberOfUses) {
                throw new ExceededNumberOfUsesForVoucher($voucher, $user);
            }
        }
    }
}
