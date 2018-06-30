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
use EsterenMaps\Entity\Routes;
use Orbitale\Component\DoctrineTools\AbstractFixture;

class RoutesFixtures extends AbstractFixture implements ORMFixtureInterface
{
    use FixtureMetadataIdGeneratorTrait;

    private $maps;
    private $factions;
    private $markers;
    private $routesTypes;

    /**
     * {@inheritdoc}
     */
    public function getOrder(): int
    {
        return 4;
    }

    /**
     * {@inheritdoc}
     */
    protected function getEntityClass(): string
    {
        return Routes::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getReferencePrefix(): ?string
    {
        return 'esterenmaps-routes-';
    }

    /**
     * {@inheritdoc}
     */
    protected function clearEntityManagerOnFlush(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getObjects()
    {
        $this->maps = [
            1 => $this->getReference('esterenmaps-maps-1'), // Tri-Kazel
        ];

        $this->routesTypes = [
            1 => $this->getReference('esterenmaps-routestypes-1'), // Chemin
            2 => $this->getReference('esterenmaps-routestypes-2'), // Route
            3 => $this->getReference('esterenmaps-routestypes-3'), // Sentier de loup
            4 => $this->getReference('esterenmaps-routestypes-4'), // Liaison maritime
            5 => $this->getReference('esterenmaps-routestypes-5'), // Voie fluviale
        ];

        $this->factions = [
            1 => $this->getReference('esterenmaps-factions-1'), // Faction test
        ];

        $this->markers = [];
        for ($i = 1; $i <= 177; ++$i) {
            if ($this->hasReference('esterenmaps-markers-'.$i)) {
                $this->markers[$i] = $this->getReference('esterenmaps-markers-'.$i);
            }
        }

        $id = 699;
        return [
            [
                'id'          => ++$id,
                'markerStart' => $this->getReference('esterenmaps-markers-700'),
                'markerEnd'   => $this->getReference('esterenmaps-markers-701'),
                'map'         => $this->maps[1],
                'routeType'   => $this->routesTypes[1],
                'name'        => 'From 0,0 to 0,10',
                'coordinates' => '[{"lat":0,"lng":0},{"lat":0,"lng":10}]',
                'distance'    => 10,
            ],
            [
                'id'          => ++$id,
                'markerStart' => $this->getReference('esterenmaps-markers-700'),
                'markerEnd'   => $this->getReference('esterenmaps-markers-703'),
                'map'         => $this->maps[1],
                'routeType'   => $this->routesTypes[1],
                'name'        => 'From 0,0 to 10,0',
                'coordinates' => '[{"lat":0,"lng":0},{"lat":10,"lng":0}]',
                'distance'    => 10,
            ],
            [
                'id'          => ++$id,
                'markerStart' => $this->getReference('esterenmaps-markers-700'),
                'markerEnd'   => $this->getReference('esterenmaps-markers-702'),
                'map'         => $this->maps[1],
                'routeType'   => $this->routesTypes[1],
                'name'        => 'From 0,0 to 10,10 (long way, no stop)',
                'coordinates' => '[{"lat":0,"lng":0},{"lat":0,"lng":-10},{"lat":20,"lng":-10},{"lat":20,"lng":10},{"lat":10,"lng":10}]',
                'distance'    => 50,
            ],
            [
                'id'          => ++$id,
                'markerStart' => $this->getReference('esterenmaps-markers-700'),
                'markerEnd'   => $this->getReference('esterenmaps-markers-702'),
                'map'         => $this->maps[1],
                'routeType'   => $this->routesTypes[5],
                'name'        => 'From 0,0 to 10,10 (short way but only water)',
                'coordinates' => '[{"lat":0,"lng":0},{"lat":10,"lng":10}]',
                'distance'    => 14.14213562,
            ],
        ];
    }
}
