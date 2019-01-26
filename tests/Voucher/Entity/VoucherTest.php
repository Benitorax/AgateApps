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

namespace Tests\Voucher\Entity;

use PHPUnit\Framework\TestCase;
use Voucher\Entity\Voucher;

class VoucherTest extends TestCase
{
    public function test invalid voucher type on creation throws exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid voucher type "invalid voucher type"');

        Voucher::create(
            'invalid voucher type', //$type,
            'unique', //$uniqueCode,
            new \DateTimeImmutable() //$validFrom
        );
    }
}
