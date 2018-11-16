<?php

namespace Voucher\Exception;

class UserHasAlreadyRedeemedThisVoucher extends RedeemException
{
    public function redeemErrorMessage(): string
    {
        return 'voucher.redeem.error.already_used';
    }
}
