<?php

namespace DataFixtures;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Orbitale\Component\DoctrineTools\AbstractFixture;
use User\Entity\Subscription;

class SubscriptionsFixtures extends AbstractFixture implements ORMFixtureInterface
{
    protected function getEntityClass(): string
    {
        return Subscription::class;
    }

    public function getOrder(): int
    {
        return 1;
    }

    protected function getObjects()
    {
        return [
            [
                'user' => $this->getReference('user-map-subscribed'),
                'type' => 'subscription.esteren_maps',
                'startsAt' => new \DateTimeImmutable(),
                'endsAt' => new \DateTimeImmutable('next month'),
            ]
        ];
    }
}
