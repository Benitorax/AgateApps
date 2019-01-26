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

namespace DataFixtures\CorahnRin;

use CorahnRin\Entity\MentalDisorder;
use DataFixtures\FixtureMetadataIdGeneratorTrait;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Orbitale\Component\DoctrineTools\AbstractFixture;

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
        return MentalDisorder::class;
    }

    protected function getReferencePrefix(): ?string
    {
        return 'corahnrin-disorder-';
    }

    protected function getObjects()
    {
        return [
            [
                'id' => 1,
                'name' => 'Frénésie',
                'description' => '',
            ],
            [
                'id' => 2,
                'name' => 'Exaltation',
                'description' => '',
            ],
            [
                'id' => 3,
                'name' => 'Mélancolie',
                'description' => '',
            ],
            [
                'id' => 4,
                'name' => 'Hallucination',
                'description' => '',
            ],
            [
                'id' => 5,
                'name' => 'Confusion mentale',
                'description' => '',
            ],
            [
                'id' => 6,
                'name' => 'Mimétisme',
                'description' => '',
            ],
            [
                'id' => 7,
                'name' => 'Obsession',
                'description' => '',
            ],
            [
                'id' => 8,
                'name' => 'Hystérie',
                'description' => '',
            ],
            [
                'id' => 9,
                'name' => 'Mysticisme',
                'description' => '',
            ],
            [
                'id' => 10,
                'name' => 'Paranoïa',
                'description' => '',
            ],
        ];
    }
}
