<?php

namespace User\Constraint;

use Symfony\Component\Validator\Constraint;
use User\Validator\UniqueSubscriptionValidator;

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
