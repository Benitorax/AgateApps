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

use CorahnRin\Entity\Book;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class BooksFixtures extends AbstractFixture implements OrderedFixtureInterface, ORMFixtureInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * Get the order of this fixture.
     */
    public function getOrder(): int
    {
        return 1;
    }

    /**
     * Load data fixtures with the passed EntityManager.
     */
    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        /** @var EntityRepository $repo */
        $repo = $this->manager->getRepository(\CorahnRin\Entity\Book::class);

        $this->fixtureObject($repo, 1, 'Livre 0 - Prologue', '');
        $this->fixtureObject($repo, 2, 'Livre 1 - Univers', '');
        $this->fixtureObject($repo, 3, 'Livre 2 - Voyages', '');
        $this->fixtureObject($repo, 4, 'Livre 2 - Voyages (Réédition)', '');
        $this->fixtureObject($repo, 5, 'Livre 3 - Dearg Intégrale', '');
        $this->fixtureObject($repo, 6, 'Livre 3 - Dearg Tome 1', '');
        $this->fixtureObject($repo, 7, 'Livre 3 - Dearg Tome 2', '');
        $this->fixtureObject($repo, 8, 'Livre 3 - Dearg Tome 3', '');
        $this->fixtureObject($repo, 9, 'Livre 3 - Dearg Tome 4', '');
        $this->fixtureObject($repo, 10, 'Livre 4 - Secrets', '');
        $this->fixtureObject($repo, 11, 'Livre 5 - Peuples', '');
        $this->fixtureObject($repo, 12, 'Le Monastère de Tuath', '');
        $this->fixtureObject($repo, 13, 'Contenu de la communauté', 'Ce contenu est par définition non-officiel.');

        $this->manager->flush();
    }

    public function fixtureObject(EntityRepository $repo, $id, $name, $description): void
    {
        $obj = null;
        $newObject = false;
        $addRef = false;
        if ($id) {
            $obj = $repo->find($id);
            if ($obj) {
                $addRef = true;
            } else {
                $newObject = true;
            }
        } else {
            $newObject = true;
        }
        if (true === $newObject) {
            $obj = new Book();
            $obj->setId($id)
                ->setName($name)
                ->setDescription($description)
            ;
            if ($id) {
                /** @var ClassMetadata $metadata */
                $metadata = $this->manager->getClassMetadata(\get_class($obj));
                $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            }
            $this->manager->persist($obj);
            $addRef = true;
        }
        if (true === $addRef && $obj) {
            $this->addReference('corahnrin-book-'.$id, $obj);
        }
    }
}
