<?php

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
use CorahnRin\Entity\CombatArt;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class CombatArtsFixtures extends AbstractFixture implements OrderedFixtureInterface, ORMFixtureInterface
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

        $repo = $this->manager->getRepository(CombatArt::class);

        /** @var Book $book */
        $book = $this->getReference('corahnrin-book-2');

        $this->fixtureObject($repo, 1, 1, 0, 'Attaque sournoise', 'Si le personnage tient un ennemi en embuscade et qu\'il touche sa cible, il inflige +5 dégâts. Il ne doit utiliser qu\'une arme courte (dague, épée courte, couteau...)', $book);
        $this->fixtureObject($repo, 2, 1, 1, 'Combat à 2 armes', 'La deuxième arme doit être courte ou de petite taille. Il ajoute +2 au bonus de son attitude de combat. Le bonus est au choix s\'il est en atittude standard. Sinon, le bonus s\'ajoute à l\'attaque en posture offensive, et en défense en posture défensive.', $book);
        $this->fixtureObject($repo, 3, 1, 0, 'Parade', 'En attitude Standard, Défensive ou Rapide, et en bénéficiant de l\'initiative, le personnage peut effectuer un jet d\'attaque contre une cible. Si le résultat est supérieur au jet d\'attaque de la cible, le personnage pare l\'attaque. Sinon, l\'attaque est résolue normalement', $book);
        $this->fixtureObject($repo, 4, 0, 1, 'Archerie', 'Niveau 5 requis en Tir et Lancer. Le personnage agit en dernier pendant le tour où il utilise cet art, mais il dispose d\'un bonus de 2 en Tir & Lancer pour son attaque, et annule tout malus sur une cible en mouvement.', $book);
        $this->fixtureObject($repo, 5, 1, 0, 'Cavalerie', 'Discipline Equitation nécessaire. Si le personnage sur une monture subit moins de 5 dégâts, il n\'est pas désarçonné. Le personnage peut également charger en premier round d\'un combat, et bénéficier d\'un bonus de +3 à son jet (+4 avec une lance)', $book);

        $this->manager->flush();
    }

    public function fixtureObject(EntityRepository $repo, $id, $melee, $ranged, $name, $description, $book)
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
            $obj = new CombatArt();
            $obj->setId($id)
                ->setName($name)
                ->setMelee($melee)
                ->setRanged($ranged)
                ->setDescription($description)
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
            $this->addReference('corahnrin-combat-art-'.$id, $obj);
        }
    }
}
