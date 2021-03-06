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

namespace Subscription\Security\Voter;

use Subscription\Repository\SubscriptionRepository;
use Subscription\SubscriptionType;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use User\Entity\User;

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
        return 0 === \mb_strpos($attribute, 'SUBSCRIBED_TO_');
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
