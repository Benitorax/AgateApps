<?php

namespace Tests\Voucher\Entity;

use PHPUnit\Framework\TestCase;
use Voucher\Entity\Voucher;

class VoucherTest extends TestCase
{
    public function test invalid voucher type on creation throws exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid voucher type "invalid voucher type"');

        Voucher::create(
            'invalid voucher type', //$type,
            'unique', //$uniqueCode,
            new \DateTimeImmutable(), //$validFrom
        );
    }
}
