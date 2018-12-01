<?php

namespace Subscription\Validator;

use Subscription\Constraint\UniqueSubscription;
use Subscription\Entity\Subscription;
use Subscription\Repository\SubscriptionRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueSubscriptionValidator extends ConstraintValidator
{
    private $repository;

    public function __construct(SubscriptionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validate($subscription, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueSubscription) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\UniqueSubscription');
        }

        if (!$subscription instanceof Subscription) {
            throw new UnexpectedTypeException($subscription, Subscription::class);
        }

        if ($this->repository->hasSimilarActiveSubscriptions($subscription)) {
            $this->context->addViolation('subscriptions.similar_exists');
        }
    }
}
