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

namespace Tests\Subscription\Validator;

use PHPUnit\Framework\TestCase;
use Subscription\Constraint\UniqueSubscription;
use Subscription\Entity\Subscription;
use Subscription\Repository\SubscriptionRepository;
use Subscription\Validator\UniqueSubscriptionValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueSubscriptionValidatorTest extends TestCase
{
    public function test constraint must be UniqueSubscription instance(): void
    {
        $constraint = new class() extends Constraint {
        };

        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "Subscription\\Constraint\\UniqueSubscription", "'.\get_class($constraint).'" given');

        $this->getValidator()->validate($this->createMock(Subscription::class), $constraint);
    }

    public function test subject must be Subscription instance(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "Subscription\Entity\Subscription", "stdClass" given');

        $this->getValidator()->validate(new \stdClass(), new UniqueSubscription());
    }

    public function test existing subscription returns violation(): void
    {
        $subscription = new class() extends Subscription {
        };

        $repo = $this->createMock(SubscriptionRepository::class);
        $repo->expects($this->once())
            ->method('hasSimilarActiveSubscriptions')
            ->with($subscription)
            ->willReturn(true)
        ;

        $context = $this->createMock(ExecutionContextInterface::class);
        $context->expects($this->once())
            ->method('addViolation')
            ->with('subscriptions.similar_exists')
        ;

        $validator = $this->getValidator($repo);
        $validator->initialize($context);

        $validator->validate($subscription, new UniqueSubscription());
    }

    private function getValidator(SubscriptionRepository $repo = null): UniqueSubscriptionValidator
    {
        return new UniqueSubscriptionValidator($repo ?: $this->createMock(SubscriptionRepository::class));
    }
}
