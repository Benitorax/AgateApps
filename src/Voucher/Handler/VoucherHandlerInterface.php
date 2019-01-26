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
use Voucher\Exception\RedeemException;
use Voucher\Exception\StopRedeemPropagation;

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
     * @throws StopRedeemPropagation to prevent executing next handlers
     */
    public function handle(Voucher $voucher, User $user): void;
}
