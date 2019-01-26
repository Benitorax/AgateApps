<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Voucher\Exception;

class NoHandlerForVoucherAndUser extends RedeemException
{
    public function redeemErrorMessage(): string
    {
        return 'voucher.redeem.error.no_handler';
    }
}
