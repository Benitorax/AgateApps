<?php

namespace Tests\Subscription\Validator;

use PHPUnit\Framework\TestCase;
use Subscription\Constraint\UniqueSubscription;
use Subscription\Entity\Subscription;
use Subscription\Repository\SubscriptionRepository;
use Subscription\Validator\UniqueSubscriptionValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueSubscriptionValidatorTest extends TestCase
{
    public function test constraint must be UniqueSubscription instance()
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessageRegExp('~^Expected argument of type "Subscription\\\Validator\\\UniqueSubscription", "Mock_Constraint_[^"]+" given$~');

        $this->getValidator()->validate($this->createMock(Subscription::class), $this->createMock(Constraint::class));
    }

    public function test subject must be Subscription instance()
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "Subscription\Entity\Subscription", "stdClass" given');

        $this->getValidator()->validate(new \stdClass(), new UniqueSubscription());
    }

    private function getValidator(): UniqueSubscriptionValidator
    {
        return new UniqueSubscriptionValidator($this->createMock(SubscriptionRepository::class));
    }
}
