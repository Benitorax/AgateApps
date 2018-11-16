<?php

namespace Voucher\Exception;

use Voucher\Entity\Voucher;
use User\Entity\User;

interface RedeemExceptionInterface extends \Throwable
{
    public function redeemErrorMessage(): string;

    public function getParameters(): array;

    public function getVoucher(): Voucher;

    public function getUser(): User;
}
