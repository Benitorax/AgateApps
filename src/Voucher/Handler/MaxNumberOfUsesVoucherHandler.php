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

namespace Voucher\Handler;

use User\Entity\User;
use Voucher\Entity\Voucher;
use Voucher\Exception\ExceededNumberOfUsesForVoucher;
use Voucher\Repository\RedeemedVoucherRepository;

class MaxNumberOfUsesVoucherHandler implements VoucherHandlerInterface
{
    private $redeemedVoucherRepository;

    public function __construct(RedeemedVoucherRepository $redeemedVoucherRepository)
    {
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
