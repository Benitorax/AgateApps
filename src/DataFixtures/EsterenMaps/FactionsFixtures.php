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

namespace DataFixtures\EsterenMaps;

use CorahnRin\Entity\Book;
use DataFixtures\FixtureMetadataIdGeneratorTrait;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use EsterenMaps\Entity\Faction;
use Orbitale\Component\DoctrineTools\AbstractFixture;

class FactionsFixtures extends AbstractFixture implements ORMFixtureInterface
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
        return Faction::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getReferencePrefix(): ?string
    {
        return 'esterenmaps-factions-';
    }

    /**
     * @return array
     */
    public function getObjects()
    {
        /** @var Book $book2 */
        $book2 = $this->getReference('corahnrin-book-2');

        return [
            [
                'id' => 1,
                'book' => $book2,
                'name' => 'Faction Test',
                'description' => 'This is just a test faction.',
                'createdAt' => \DateTime::createFromFormat('Y-m-d H:i:s', '2015-07-10 20:49:05'),
                'updatedAt' => \DateTime::createFromFormat('Y-m-d H:i:s', '2015-07-10 20:49:05'),
            ],
        ];
    }
}
