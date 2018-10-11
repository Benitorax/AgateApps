<?php

namespace User\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use User\Entity\Subscription;

class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    public function findNotFinishedSimilarSubscriptions(Subscription $subscription): array
    {
        return $this->createQueryBuilder('subscription')
            ->where('subscription.user = :user')
            ->andWhere('subscription.type = :type')
            ->andWhere('subscription.endsAt >= :now')
            ->setParameter('user', $subscription->getUser())
            ->setParameter('type', $subscription->getType())
            ->setParameter('now', new \DateTimeImmutable('now'))
            ->getQuery()
            ->getResult()
        ;
    }
}
