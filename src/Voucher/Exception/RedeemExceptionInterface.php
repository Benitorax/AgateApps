<?php

namespace Voucher\Exception;

use User\Entity\User;
use Voucher\Entity\Voucher;

interface RedeemExceptionInterface extends \Throwable
{
    public function redeemErrorMessage(): string;

    public function getParameters(): array;

    public function getVoucher(): Voucher;

    public function getUser(): User;
}
