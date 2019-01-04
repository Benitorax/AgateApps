<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DataFixtures\EsterenMaps;

use DataFixtures\FixtureMetadataIdGeneratorTrait;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EsterenMaps\Entity\ZoneType;
use Orbitale\Component\DoctrineTools\AbstractFixture;

class ZonesTypesFixtures extends AbstractFixture implements ORMFixtureInterface
{
    use FixtureMetadataIdGeneratorTrait;

    /**
     * {@inheritdoc}
     */
    protected function getEntityClass(): string
    {
        return ZoneType::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getReferencePrefix(): ?string
    {
        return 'esterenmaps-zonestypes-';
    }

    /**
     * {@inheritdoc}
     */
    protected function getObjects()
    {
        return [
            [
                'id' => 1,
                'parent' => null,
                'name' => 'Political',
                'description' => '',
                'color' => '',
                'createdAt' => \DateTime::createFromFormat('Y-m-d H:i:s', '2015-07-10 20:49:05'),
                'updatedAt' => \DateTime::createFromFormat('Y-m-d H:i:s', '2015-07-10 20:49:05'),
            ],
            [
                'id' => 2,
                'parent' => function (ZoneType $obj, AbstractFixture $f, ObjectManager $manager) {
                    $ref = $manager->merge($f->getReference('esterenmaps-zonestypes-1'));
                    $obj->setParent($ref);

                    return $ref;
                },
                'name' => 'Kingdom',
                'description' => '',
                'color' => '#E05151',
                'createdAt' => \DateTime::createFromFormat('Y-m-d H:i:s', '2015-07-10 20:49:05'),
                'updatedAt' => \DateTime::createFromFormat('Y-m-d H:i:s', '2015-07-10 20:49:05'),
            ],
        ];
    }
}
