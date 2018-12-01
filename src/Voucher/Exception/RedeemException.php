<?php

namespace Voucher\Exception;

use User\Entity\User;
use Voucher\Entity\Voucher;

abstract class RedeemException extends \RuntimeException implements RedeemExceptionInterface
{
    private $voucher;
    private $user;

    public function getParameters(): array
    {
        return [
            '%username%' => $this->user->getUsername(),
            '%voucher_type%' => $this->voucher->getType(),
            '%voucher_code%' => $this->voucher->getUniqueCode(),
        ];
    }

    public function __construct(Voucher $voucher, User $user, \Throwable $previous = null)
    {
        $this->voucher = $voucher;
        $this->user = $user;
        parent::__construct(\strtr($this->redeemErrorMessage(), $this->getParameters()), 0, $previous);
    }

    public function getVoucher(): Voucher
    {
        return $this->voucher;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
