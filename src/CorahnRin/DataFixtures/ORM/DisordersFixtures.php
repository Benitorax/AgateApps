<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\DataFixtures\ORM;

use CorahnRin\Entity\Disorders;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Orbitale\Component\DoctrineTools\AbstractFixture;
use Agate\Doctrine\FixtureMetadataIdGeneratorTrait;

class DisordersFixtures extends AbstractFixture implements ORMFixtureInterface
{
    use FixtureMetadataIdGeneratorTrait;

    /**
     * {@inheritdoc}
     */
    public function getOrder(): int
    {
        return 2;
    }

    /**
     * {@inheritdoc}
     */
    protected function getEntityClass(): string
    {
        return Disorders::class;
    }

    protected function getReferencePrefix(): ?string
    {
        return 'corahnrin-disorder-';
    }

    protected function getObjects()
    {
        return [
            [
                'id'          => 1,
                'name'        => 'Frénésie',
                'description' => '',
            ],
            [
                'id'          => 2,
                'name'        => 'Exaltation',
                'description' => '',
            ],
            [
                'id'          => 3,
                'name'        => 'Mélancolie',
                'description' => '',
            ],
            [
                'id'          => 4,
                'name'        => 'Hallucination',
                'description' => '',
            ],
            [
                'id'          => 5,
                'name'        => 'Confusion mentale',
                'description' => '',
            ],
            [
                'id'          => 6,
                'name'        => 'Mimétisme',
                'description' => '',
            ],
            [
                'id'          => 7,
                'name'        => 'Obsession',
                'description' => '',
            ],
            [
                'id'          => 8,
                'name'        => 'Hystérie',
                'description' => '',
            ],
            [
                'id'          => 9,
                'name'        => 'Mysticisme',
                'description' => '',
            ],
            [
                'id'          => 10,
                'name'        => 'Paranoïa',
                'description' => '',
            ],
        ];
    }
}
