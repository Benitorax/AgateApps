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
