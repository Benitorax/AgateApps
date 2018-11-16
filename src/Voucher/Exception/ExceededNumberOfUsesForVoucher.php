<?php

namespace Voucher\Exception;

class ExceededNumberOfUsesForVoucher extends RedeemException
{
    public function redeemErrorMessage(): string
    {
        return 'voucher.redeem.error.exceeded_uses';
    }
}
