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

use User\Entity\User;
use Voucher\Entity\Voucher;

interface RedeemExceptionInterface extends \Throwable
{
    public function redeemErrorMessage(): string;

    public function getParameters(): array;

    public function getVoucher(): Voucher;

    public function getUser(): User;
}
