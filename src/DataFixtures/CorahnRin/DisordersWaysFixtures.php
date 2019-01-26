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

use CorahnRin\Data\Ways;
use CorahnRin\Entity\MentalDisorderWay;
use DataFixtures\FixtureMetadataIdGeneratorTrait;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Orbitale\Component\DoctrineTools\AbstractFixture;

class DisordersWaysFixtures extends AbstractFixture implements ORMFixtureInterface
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
        return MentalDisorderWay::class;
    }

    protected function getObjects()
    {
        return [
            // 1 - Frénésie
            [
                'disorder' => $this->getReference('corahnrin-disorder-1'),
                'way' => Ways::COMBATIVENESS,
                'major' => true,
            ],

            // 2 - Exaltation
            [
                'disorder' => $this->getReference('corahnrin-disorder-2'),
                'way' => Ways::COMBATIVENESS,
                'major' => true,
            ],
            [
                'disorder' => $this->getReference('corahnrin-disorder-2'),
                'way' => Ways::CONVICTION,
                'major' => false,
            ],

            // 3 - Mélancolie
            [
                'disorder' => $this->getReference('corahnrin-disorder-3'),
                'way' => Ways::CONVICTION,
                'major' => true,
            ],
            [
                'disorder' => $this->getReference('corahnrin-disorder-3'),
                'way' => Ways::COMBATIVENESS,
                'major' => false,
            ],
            [
                'disorder' => $this->getReference('corahnrin-disorder-3'),
                'way' => Ways::REASON,
                'major' => false,
            ],

            // 4 - Hallucination
            [
                'disorder' => $this->getReference('corahnrin-disorder-4'),
                'way' => Ways::CREATIVITY,
                'major' => true,
            ],
            [
                'disorder' => $this->getReference('corahnrin-disorder-4'),
                'way' => Ways::EMPATHY,
                'major' => true,
            ],

            // 5 - Confusion mentale
            [
                'disorder' => $this->getReference('corahnrin-disorder-5'),
                'way' => Ways::CREATIVITY,
                'major' => true,
            ],
            [
                'disorder' => $this->getReference('corahnrin-disorder-5'),
                'way' => Ways::REASON,
                'major' => false,
            ],

            // 6 - Mimétisme
            [
                'disorder' => $this->getReference('corahnrin-disorder-6'),
                'way' => Ways::CREATIVITY,
                'major' => false,
            ],

            // 7 - Obsession
            [
                'disorder' => $this->getReference('corahnrin-disorder-7'),
                'way' => Ways::REASON,
                'major' => true,
            ],
            [
                'disorder' => $this->getReference('corahnrin-disorder-7'),
                'way' => Ways::CREATIVITY,
                'major' => false,
            ],

            // 8 - Hystérie
            [
                'disorder' => $this->getReference('corahnrin-disorder-8'),
                'way' => Ways::EMPATHY,
                'major' => true,
            ],

            // 9 - Mysticisme
            [
                'disorder' => $this->getReference('corahnrin-disorder-9'),
                'way' => Ways::EMPATHY,
                'major' => true,
            ],
            [
                'disorder' => $this->getReference('corahnrin-disorder-9'),
                'way' => Ways::CONVICTION,
                'major' => true,
            ],

            // 10 - Paranoïa
            [
                'disorder' => $this->getReference('corahnrin-disorder-10'),
                'way' => Ways::REASON,
                'major' => true,
            ],
            [
                'disorder' => $this->getReference('corahnrin-disorder-10'),
                'way' => Ways::EMPATHY,
                'major' => false,
            ],
        ];
    }
}
