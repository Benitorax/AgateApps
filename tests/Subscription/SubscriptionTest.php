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

namespace Tests\Subscription;

use PHPUnit\Framework\TestCase;
use Subscription\Entity\Subscription;
use User\Entity\User;

class SubscriptionTest extends TestCase
{
    public function test subscription creation with wrong type throws exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Subscription type wrong_type does not exist.');

        Subscription::create($this->createMock(User::class), 'wrong_type', new \DateTimeImmutable());
    }
}
