<?php

namespace Voucher\Handler;

use User\Entity\User;
use Voucher\Entity\Voucher;
use Voucher\Exception\UserHasAlreadyRedeemedThisVoucher;
use Voucher\Repository\RedeemedVoucherRepository;

class AlreadyUsedVoucherHandler implements VoucherHandlerInterface
{
    private $redeemedVoucherRepository;

    public function __construct(RedeemedVoucherRepository $redeemedVoucherRepository)
    {
        $this->redeemedVoucherRepository = $redeemedVoucherRepository;
    }

    public static function getPriority(): int
    {
        // Executed after defaults one because it induces a db query.
        return 80;
    }

    public function supports(Voucher $voucher, User $user): bool
    {
        return true;
    }

    public function handle(Voucher $voucher, User $user): void
    {
        $similar = $this->redeemedVoucherRepository->findByVoucherAndUser($voucher, $user);

        if (\count($similar) > 0) {
            throw new UserHasAlreadyRedeemedThisVoucher($voucher, $user);
        }
    }
}