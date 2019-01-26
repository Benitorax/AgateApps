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

namespace Voucher\Exception;

class ExceededNumberOfUsesForVoucher extends RedeemException
{
    public function redeemErrorMessage(): string
    {
        return 'voucher.redeem.error.exceeded_uses';
    }
}
