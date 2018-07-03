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
use EsterenMaps\Entity\Maps;
use Orbitale\Component\DoctrineTools\AbstractFixture;

class MapsFixtures extends AbstractFixture implements ORMFixtureInterface
{
    use FixtureMetadataIdGeneratorTrait;

    /**
     * {@inheritdoc}
     */
    protected function getEntityClass(): string
    {
        return Maps::class;
    }

    protected function getReferencePrefix(): ?string
    {
        return 'esterenmaps-maps-';
    }

    /**
     * {@inheritdoc}
     */
    protected function getObjects()
    {
        return [
            [
                'id'               => 1,
                'name'             => 'Tri-Kazel',
                'nameSlug'         => 'tri-kazel',
                'image'            => 'uploads/maps/esteren_map.jpg',
                'description'      => 'Carte de Tri-Kazel officielle, réalisée par Chris.',
                'maxZoom'          => 5,
                'startZoom'        => 2,
                'startX'           => 65,
                'startY'           => 85,
                'coordinatesRatio' => 5,
                'bounds'           => '[[134,-1],[-1,169]]',
                'createdAt'        => \DateTime::createFromFormat('Y-m-d H:i:s', '2015-07-10 20:49:05'),
                'updatedAt'        => \DateTime::createFromFormat('Y-m-d H:i:s', '2016-02-23 14:46:09'),
            ],
        ];
    }
}
