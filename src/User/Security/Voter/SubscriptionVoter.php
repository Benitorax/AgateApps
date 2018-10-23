<?php

namespace User\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use User\Entity\User;
use User\Repository\SubscriptionRepository;
use User\Subscription\SubscriptionType;

class SubscriptionVoter extends Voter
{
    private $subscriptionRepository;

    /**
     * Only here to avoid retrieving subscriptions twice for the same user in a same request call.
     *
     * @var string[][]
     */
    private $providedPermissionsForUser = [];

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    protected function supports($attribute, $subject)
    {
        return 0 === strpos($attribute, 'SUBSCRIBED_TO_');
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $userId = $user->getId();

        if (isset($this->providedPermissionsForUser[$userId])) {
            return \in_array($attribute, $this->providedPermissionsForUser[$userId], true);
        }

        $this->providedPermissionsForUser[$userId] = [];

        foreach ($this->subscriptionRepository->getUserActiveSubscriptions($user) as $subscription) {
            $this->providedPermissionsForUser[$userId] += SubscriptionType::TYPES_PERMISSIONS[$subscription->getType()];
        }

        return \in_array($attribute, $this->providedPermissionsForUser[$userId], true);
    }
}
