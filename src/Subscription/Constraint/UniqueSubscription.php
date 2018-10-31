<?php

namespace Subscription\Constraint;

use Subscription\Validator\UniqueSubscriptionValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class UniqueSubscription extends Constraint
{
    public function getTargets()
    {
        return [self::CLASS_CONSTRAINT];
    }

    public function validatedBy()
    {
        return UniqueSubscriptionValidator::class;
    }
}
