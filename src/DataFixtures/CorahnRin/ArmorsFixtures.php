<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DataFixtures\CorahnRin;

use CorahnRin\Entity\Armor;
use CorahnRin\Entity\Book;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class ArmorsFixtures extends AbstractFixture implements OrderedFixtureInterface, ORMFixtureInterface
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
        return 2;
    }

    /**
     * Load data fixtures with the passed EntityManager.
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $repo = $this->manager->getRepository(\CorahnRin\Entity\Armor::class);

        /** @var Book $book */
        $book = $this->getReference('corahnrin-book-2');
        $this->fixtureObject($repo, 1, 'Bouclier rond', 'bois renforcé de métal', 1, 20, 'FR', $book);
        $this->fixtureObject($repo, 2, 'Bouclier Osag', 'bois et renforts de métal, rectangulaire', 1, 20, 'CO', $book);
        $this->fixtureObject($repo, 3, 'Écu Hilderin', 'en métal, de forme triangulaire', 1, 30, 'RA', $book);
        $this->fixtureObject($repo, 4, 'Cotte de cuir', '', 1, 20, 'FR', $book);
        $this->fixtureObject($repo, 5, 'Cotte de cuir clouté', '', 2, 30, 'CO', $book);
        $this->fixtureObject($repo, 6, 'Cotte de mailles', '', 3, 0, 'CO', $book);
        $this->fixtureObject($repo, 7, 'Cuirasse en roseau', '', 2, 10, 'RA', $book);
        $this->fixtureObject($repo, 8, 'Cuirasse continentale', ' en lamelles, cuir et métal', 3, 100, 'RA', $book);
        $this->fixtureObject($repo, 9, 'Armure de plaques', '', 4, 300, 'EX', $book);
        $this->fixtureObject($repo, 10, 'Cagoule de cuir', ' (Chaque armure est fournie avec le casque. Ne pas le porter suscite un malus de 1 au score de Protection, minimum 1)', 0, 4, 'FR', $book);
        $this->fixtureObject($repo, 11, 'Cagoule de mailles', ' (Chaque armure est fournie avec le casque. Ne pas le porter suscite un malus de 1 au score de Protection, minimum 1)', 0, 8, 'CO', $book);
        $this->fixtureObject($repo, 12, 'Casque Osag', ' (Chaque armure est fournie avec le casque. Ne pas le porter suscite un malus de 1 au score de Protection, minimum 1)', 0, 10, 'FR', $book);
        $this->fixtureObject($repo, 13, 'Casque ouvert', ' (Chaque armure est fournie avec le casque. Ne pas le porter suscite un malus de 1 au score de Protection, minimum 1)', 0, 20, 'CO', $book);
        $this->fixtureObject($repo, 14, 'Heaume', ' (Chaque armure est fournie avec le casque. Ne pas le porter suscite un malus de 1 au score de Protection, minimum 1)', 0, 40, 'RA', $book);
        $this->fixtureObject($repo, 15, 'Pavois du temple', 'gravé du symbole de l\'Unique', 1, 20, 'CO', $book);

        $this->manager->flush();
    }

    public function fixtureObject(EntityRepository $repo, $id, $name, $description, $protection, $price, $availability, $book)
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
            $obj = new Armor();
            $obj->setId($id)
                ->setName($name)
                ->setDescription($description)
                ->setProtection($protection)
                ->setPrice($price)
                ->setAvailability($availability)
                ->setBook($book)
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
            $this->addReference('corahnrin-armor-'.$id, $obj);
        }
    }
}
