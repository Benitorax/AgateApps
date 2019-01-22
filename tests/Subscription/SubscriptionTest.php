<?php

namespace Tests\Subscription;

use PHPUnit\Framework\TestCase;
use Subscription\Entity\Subscription;
use User\Entity\User;

class SubscriptionTest extends TestCase
{
    public function test subscription creation with wrong type throws exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Subscription type wrong_type does not exist.');

        Subscription::create($this->createMock(User::class), 'wrong_type', new \DateTimeImmutable());
    }
}
