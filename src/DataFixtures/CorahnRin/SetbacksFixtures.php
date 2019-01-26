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
use CorahnRin\Entity\CharacterProperties\Bonuses;
use CorahnRin\Entity\Setbacks;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class SetbacksFixtures extends AbstractFixture implements OrderedFixtureInterface, ORMFixtureInterface
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
        return 3;
    }

    /**
     * Load data fixtures with the passed EntityManager.
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $repo = $this->manager->getRepository(Setbacks::class);

        /** @var Book $book */
        $book = $this->getReference('corahnrin-book-2');

        $this->fixtureObject($repo, 1, 'Poisse', 'Tirer une deuxième fois, ignorer les 1 supplémentaires', '', $book);
        $this->fixtureObject($repo, 2, 'Séquelle', '-1 Vigueur, et une séquelle physique (cicatrice...)', Bonuses::STAMINA, $book);
        $this->fixtureObject($repo, 3, 'Adversaire', 'Le personnage s\'est fait un ennemi (à la discrétion du MJ)', '', $book);
        $this->fixtureObject($repo, 4, 'Rumeur', 'Une information, vraie ou non, circule à propos du personnage', '', $book);
        $this->fixtureObject($repo, 5, 'Amour tragique', '+1 point de Trauma définitif, mauvais souvenir', Bonuses::TRAUMA, $book);
        $this->fixtureObject($repo, 6, 'Maladie', '-1 Vigueur, mais a survécu à une maladie normalement mortelle', Bonuses::STAMINA, $book);
        $this->fixtureObject($repo, 7, 'Violence', '+1 point de Trauma définitif, souvenir violent, gore, horrible...', Bonuses::TRAUMA, $book);
        $this->fixtureObject($repo, 8, 'Solitude', 'Les proches, amis ou famille du personnage sont morts de façon douteuse', '', $book);
        $this->fixtureObject($repo, 9, 'Pauvreté', 'Le personnage ne possède qu\'une mauvaise arme, ou outil, a des dettes d\'héritage, de vol... Il n\'a plus d\'argent, sa famille a été ruinée ou lui-même est ruiné d\'une façon ou d\'une autre, et aucun évènement ou avantage ne peut y remédier.', Bonuses::MONEY_0, $book, [
            $this->getReference('corahnrin-avantage-4'),
            $this->getReference('corahnrin-avantage-5'),
            $this->getReference('corahnrin-avantage-6'),
            $this->getReference('corahnrin-avantage-7'),
            $this->getReference('corahnrin-avantage-8'),
            $this->getReference('corahnrin-avantage-47'),
        ]);
        $this->fixtureObject($repo, 10, 'Chance', 'Le personnage est passé à côté de la catastrophe !', '', $book);

        $this->manager->flush();
    }

    public function fixtureObject(EntityRepository $repo, $id, $name, $description, $malus, $book, array $disabledAdvantages = [])
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
            $obj = new Setbacks();
            $obj->setId($id)
                ->setName($name)
                ->setDescription($description)
                ->setBook($book)
                ->setMalus($malus)
            ;
            $obj->setDisabledAdvantages($disabledAdvantages);
            if ($id) {
                /** @var ClassMetadata $metadata */
                $metadata = $this->manager->getClassMetadata(\get_class($obj));
                $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            }
            $this->manager->persist($obj);
            $addRef = true;
        }
        if (true === $addRef && $obj) {
            $this->addReference('corahnrin-setback-'.$id, $obj);
        }
    }
}
