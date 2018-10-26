<?php

namespace User\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use User\Constraint\UniqueSubscription;
use User\Entity\Subscription;
use User\Repository\SubscriptionRepository;

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
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\UniqueSubscriptionValidator');
        }

        if (!$subscription instanceof Subscription) {
            throw new UnexpectedTypeException($subscription, Subscription::class);
        }

        if ($this->repository->hasSimilarActiveSubscriptions($subscription)) {
            $this->context->addViolation('subscriptions.similar_exists');
        }
    }
}
