<?php

namespace Voucher\Exception;

class StopRedeemPropagation extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Voucher handlers propagation stopped.');
    }
}
