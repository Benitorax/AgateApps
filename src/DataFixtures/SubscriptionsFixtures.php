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

namespace DataFixtures;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Orbitale\Component\DoctrineTools\AbstractFixture;
use Subscription\Entity\Subscription;

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
            ],
        ];
    }
}
