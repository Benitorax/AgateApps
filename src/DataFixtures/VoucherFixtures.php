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
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Voucher\Entity\Voucher;

class VoucherFixtures extends AbstractFixture implements ORMFixtureInterface
{
    protected function createNewInstance(array $data)
    {
        return Voucher::create($data['type'], $data['uniqueCode'], $data['validFrom']);
    }

    public function load(ObjectManager $manager): void
    {
        $manager->persist($this->createNewInstance([
            'type' => 'voucher.esteren_maps',
            'uniqueCode' => 'ESTEREN_MAPS_3M',
            'validFrom' => new \DateTimeImmutable(),
        ]));

        $manager->flush();
    }
}
