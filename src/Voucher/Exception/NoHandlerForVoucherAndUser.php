<?php

namespace Voucher\Exception;

class NoHandlerForVoucherAndUser extends RedeemException
{
    public function redeemErrorMessage(): string
    {
        return 'voucher.redeem.error.no_handler';
    }
}
