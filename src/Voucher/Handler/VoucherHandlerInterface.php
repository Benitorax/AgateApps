<?php

namespace Voucher\Handler;

use Voucher\Entity\Voucher;
use Voucher\Exception\RedeemException;
use Voucher\Exception\StopRedeemPropagation;
use User\Entity\User;

interface VoucherHandlerInterface
{
    /**
     * Used to specify the order in which handlers are checked.
     * The higher priority, the sooner it handles the voucher.
     */
    public static function getPriority(): int;

    /**
     * Silently checks if this handler can be used for this voucher and user.
     * Should not throw exception.
     */
    public function supports(Voucher $voucher, User $user): bool;

    /**
     * Executes an action for this voucher to be redeemed.
     * May throw an exception if redeem failed.
     *
     * @throws RedeemException
     * @throws StopRedeemPropagation To prevent executing next handlers.
     */
    public function handle(Voucher $voucher, User $user): void;
}
