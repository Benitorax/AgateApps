<?php

namespace Voucher\Exception;

class SaveError extends RedeemException
{
    public function redeemErrorMessage(): string
    {
        return 'voucher.redeem.error.save_error';
    }
}
