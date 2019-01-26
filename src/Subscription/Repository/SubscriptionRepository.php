<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Subscription\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Subscription\Entity\Subscription;
use User\Entity\User;

class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    public function hasSimilarActiveSubscriptions(Subscription $subscription): bool
    {
        $results = $this->createQueryBuilder('subscription')
            ->select('subscription.id')
            ->where('subscription.user = :user')
            ->andWhere('subscription.type = :type')
            ->andWhere('subscription.startsAt <= :now')
            ->andWhere('subscription.endsAt >= :now')
            ->setParameter('user', $subscription->getUser())
            ->setParameter('type', $subscription->getType())
            ->setParameter('now', new \DateTimeImmutable('now'))
            ->setMaxResults(1)
            ->getQuery()
            ->getArrayResult()
        ;

        return \count($results) > 0;
    }

    /**
     * @return Subscription[]
     */
    public function getUserActiveSubscriptions(User $user): array
    {
        return $this->createQueryBuilder('subscription')
            ->where('subscription.user = :user')
            ->andWhere('subscription.startsAt <= :now')
            ->andWhere('subscription.endsAt >= :now')
            ->setParameter('user', $user)
            ->setParameter('now', new \DateTimeImmutable('now'))
            ->getQuery()
            ->getResult()
        ;
    }
}
