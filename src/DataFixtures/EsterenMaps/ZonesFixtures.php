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
use Orbitale\Component\DoctrineTools\AbstractFixture;
use EsterenMaps\Entity\Zones;

class ZonesFixtures extends AbstractFixture implements ORMFixtureInterface
{
    use FixtureMetadataIdGeneratorTrait;

    /**
     * {@inheritdoc}
     */
    public function getOrder(): int
    {
        return 3;
    }

    /**
     * {@inheritdoc}
     */
    protected function getEntityClass(): string
    {
        return Zones::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getReferencePrefix(): ?string
    {
        return 'esterenmaps-zones-';
    }

    /**
     * {@inheritdoc}
     */
    protected function getObjects()
    {
        $map1 = $this->getReference('esterenmaps-maps-1');

//        $zoneType1 = $this->getReference('esterenmaps-zonestypes-1');// Political
        $zoneType2 = $this->getReference('esterenmaps-zonestypes-2');// Kingdom

        $faction1  = $this->getReference('esterenmaps-factions-1');

        return [
            [
                'id'          => 1,
                'map'         => $map1,
                'faction'     => $faction1,
                'zoneType'    => $zoneType2,
                'name'        => 'Kingdom test',
                'description' => '',
                'coordinates' => '[{"lat":25,"lng":35},{"lat":35,"lng":35},{"lat":35,"lng":40,{"lat":25,"lng":40}]',
                'createdAt'   => \DateTime::createFromFormat('Y-m-d H:i:s', '2015-03-14 15:26:35'),
                'updatedAt'   => \DateTime::createFromFormat('Y-m-d H:i:s', '2015-12-27 17:38:09'),
            ],
        ];
    }
}
