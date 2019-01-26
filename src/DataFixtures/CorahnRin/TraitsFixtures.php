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
use CorahnRin\Entity\Book;
use CorahnRin\Entity\PersonalityTrait;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class TraitsFixtures extends AbstractFixture implements OrderedFixtureInterface, ORMFixtureInterface
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
    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $repo = $this->manager->getRepository(PersonalityTrait::class);

        /** @var Book $book */
        $book = $this->getReference('corahnrin-book-2');

        $way1 = Ways::COMBATIVENESS;
        $way2 = Ways::CREATIVITY;
        $way3 = Ways::EMPATHY;
        $way4 = Ways::REASON;
        $way5 = Ways::CONVICTION;

        $this->fixtureObject($repo, 1, $way1, 'Combatif', 'Combative', 1, 1, $book);
        $this->fixtureObject($repo, 2, $way1, 'Optimiste', 'Optimiste', 1, 1, $book);
        $this->fixtureObject($repo, 3, $way1, 'Dynamique', 'Dynamique', 1, 1, $book);
        $this->fixtureObject($repo, 4, $way1, 'Courageux', 'Courageuse', 1, 1, $book);
        $this->fixtureObject($repo, 5, $way1, 'Pugnace', 'Pugnace', 1, 1, $book);
        $this->fixtureObject($repo, 6, $way1, 'Calme', 'Calme', 1, 0, $book);
        $this->fixtureObject($repo, 7, $way1, 'Flegmatique', 'Flegmatique', 1, 0, $book);
        $this->fixtureObject($repo, 8, $way1, 'Paisible', 'Paisible', 1, 0, $book);
        $this->fixtureObject($repo, 9, $way1, 'Pondéré', 'Pondérée', 1, 0, $book);
        $this->fixtureObject($repo, 10, $way1, 'Impulsif', 'Impulsive', 0, 1, $book);
        $this->fixtureObject($repo, 11, $way1, 'Outrecuidant', 'Outrecuidante', 0, 1, $book);
        $this->fixtureObject($repo, 12, $way1, 'Fier', 'Fière', 0, 1, $book);
        $this->fixtureObject($repo, 13, $way1, 'Buté', 'Butée', 0, 1, $book);
        $this->fixtureObject($repo, 14, $way1, 'Orgueilleux', 'Orgueilleuse', 0, 1, $book);
        $this->fixtureObject($repo, 15, $way1, 'Vaniteux', 'Vaniteuse', 0, 1, $book);
        $this->fixtureObject($repo, 16, $way1, 'Pessimiste', 'Pessimiste', 0, 0, $book);
        $this->fixtureObject($repo, 17, $way1, 'Mou', 'Molle', 0, 0, $book);
        $this->fixtureObject($repo, 18, $way1, 'Triste', 'Triste', 0, 0, $book);
        $this->fixtureObject($repo, 19, $way1, 'Faible caractère', 'Faible caractère', 0, 0, $book);
        $this->fixtureObject($repo, 20, $way1, 'Peureux', 'Peureuse', 0, 0, $book);
        $this->fixtureObject($repo, 21, $way1, 'Mauvaise estime', 'Mauvaise estime', 0, 0, $book);
        $this->fixtureObject($repo, 22, $way1, 'Lâche', 'Lâche', 0, 0, $book);
        $this->fixtureObject($repo, 23, $way2, 'Inventif', 'Inventive', 1, 1, $book);
        $this->fixtureObject($repo, 24, $way2, 'Original', 'Originale', 1, 1, $book);
        $this->fixtureObject($repo, 25, $way2, 'Débrouillard', 'Débrouillarde', 1, 1, $book);
        $this->fixtureObject($repo, 26, $way2, 'Drôle', 'Drôle', 1, 1, $book);
        $this->fixtureObject($repo, 27, $way2, 'Poète', 'Poète', 1, 1, $book);
        $this->fixtureObject($repo, 28, $way2, 'Sérieux', 'Sérieuse', 1, 0, $book);
        $this->fixtureObject($repo, 29, $way2, 'Traditionaliste', 'Traditionaliste', 1, 0, $book);
        $this->fixtureObject($repo, 30, $way2, 'Procédurier', 'Procédurière', 1, 0, $book);
        $this->fixtureObject($repo, 31, $way2, 'Discipliné', 'Disciplinée', 1, 0, $book);
        $this->fixtureObject($repo, 32, $way2, 'Anticonformiste', 'Anticonformiste', 0, 1, $book);
        $this->fixtureObject($repo, 33, $way2, 'Rebelle', 'Rebelle', 0, 1, $book);
        $this->fixtureObject($repo, 34, $way2, 'Indiscipliné', 'Indisciplinée', 0, 1, $book);
        $this->fixtureObject($repo, 35, $way2, 'Excentrique', 'Excentrique', 0, 1, $book);
        $this->fixtureObject($repo, 36, $way2, 'Menteur', 'Menteuse', 0, 1, $book);
        $this->fixtureObject($repo, 37, $way2, 'Empoté', 'Empotée', 0, 0, $book);
        $this->fixtureObject($repo, 38, $way2, 'Esprit étriqué', 'Esprit étriqué', 0, 0, $book);
        $this->fixtureObject($repo, 39, $way2, 'Ascétique', 'Ascétique', 0, 0, $book);
        $this->fixtureObject($repo, 40, $way2, 'Rigide', 'Rigide', 0, 0, $book);
        $this->fixtureObject($repo, 41, $way3, 'Réceptif', 'Réceptive', 1, 1, $book);
        $this->fixtureObject($repo, 42, $way3, 'Sensible', 'Sensible', 1, 1, $book);
        $this->fixtureObject($repo, 43, $way3, 'Intuitif', 'Intuitive', 1, 1, $book);
        $this->fixtureObject($repo, 44, $way3, 'Extraverti', 'Extravertie', 1, 1, $book);
        $this->fixtureObject($repo, 45, $way3, 'Contrôle de ses émotions', 'Contrôle de ses émotions', 1, 0, $book);
        $this->fixtureObject($repo, 46, $way3, 'Peu influençable', 'Peu influençable', 1, 0, $book);
        $this->fixtureObject($repo, 47, $way3, 'Émotif', 'Émotive', 0, 1, $book);
        $this->fixtureObject($repo, 48, $way3, 'Influençable', 'Influençable', 0, 1, $book);
        $this->fixtureObject($repo, 49, $way3, 'Bavard', 'Bavarde', 0, 1, $book);
        $this->fixtureObject($repo, 50, $way3, 'Austère', 'Austère', 0, 0, $book);
        $this->fixtureObject($repo, 51, $way3, 'Insensible', 'Insensible', 0, 0, $book);
        $this->fixtureObject($repo, 52, $way3, 'Renfermé', 'Renfermée', 0, 0, $book);
        $this->fixtureObject($repo, 53, $way3, 'Taciturne', 'Taciturne', 0, 0, $book);
        $this->fixtureObject($repo, 54, $way3, 'Froid', 'Froide', 0, 0, $book);
        $this->fixtureObject($repo, 55, $way3, 'Individualiste', 'Individualiste', 0, 0, $book);
        $this->fixtureObject($repo, 56, $way4, 'Réfléchi', 'Réfléchie', 1, 1, $book);
        $this->fixtureObject($repo, 57, $way4, 'Ingénieux', 'Ingénieuse', 1, 1, $book);
        $this->fixtureObject($repo, 58, $way4, 'Prudent', 'Prudente', 1, 1, $book);
        $this->fixtureObject($repo, 59, $way4, 'Logique', 'Logique', 1, 1, $book);
        $this->fixtureObject($repo, 60, $way4, 'Concentré', 'Concentrée', 1, 1, $book);
        $this->fixtureObject($repo, 61, $way4, 'Spontané', 'Spontanée', 1, 0, $book);
        $this->fixtureObject($repo, 62, $way4, 'Téméraire', 'Téméraire', 1, 0, $book);
        $this->fixtureObject($repo, 63, $way4, 'Peu entravé', 'Peu entravée', 1, 0, $book);
        $this->fixtureObject($repo, 64, $way4, 'Abstraction', 'Abstraction', 0, 1, $book);
        $this->fixtureObject($repo, 65, $way4, 'Replié sur soi', 'Repliée sur soi', 0, 1, $book);
        $this->fixtureObject($repo, 66, $way4, 'Précautionneux', 'Précautionneuse', 0, 1, $book);
        $this->fixtureObject($repo, 67, $way4, 'Hésitant', 'Hésitante', 0, 1, $book);
        $this->fixtureObject($repo, 68, $way4, 'Distrait', 'Distraite', 0, 0, $book);
        $this->fixtureObject($repo, 69, $way4, 'Imprudent', 'Imprudente', 0, 0, $book);
        $this->fixtureObject($repo, 70, $way4, 'Irréfléchi', 'Irréfléchie', 0, 0, $book);
        $this->fixtureObject($repo, 71, $way5, 'Droit', 'Droite', 1, 1, $book);
        $this->fixtureObject($repo, 72, $way5, 'Persévérant', 'Persévérante', 1, 1, $book);
        $this->fixtureObject($repo, 73, $way5, 'Loyal', 'Loyale', 1, 1, $book);
        $this->fixtureObject($repo, 74, $way5, 'Incorruptible', 'Incorruptible', 1, 1, $book);
        $this->fixtureObject($repo, 75, $way5, 'Généreux', 'Généreuse', 1, 1, $book);
        $this->fixtureObject($repo, 76, $way5, 'Libre', 'Libre', 1, 0, $book);
        $this->fixtureObject($repo, 77, $way5, 'Indépendant', 'Indépendante', 1, 0, $book);
        $this->fixtureObject($repo, 78, $way5, 'Rigide', 'Rigide', 0, 1, $book);
        $this->fixtureObject($repo, 79, $way5, 'Intolérant', 'Intolérante', 0, 1, $book);
        $this->fixtureObject($repo, 80, $way5, 'Fanatique', 'Fanatique', 0, 1, $book);
        $this->fixtureObject($repo, 81, $way5, 'Influençable', 'Influençable', 0, 1, $book);
        $this->fixtureObject($repo, 82, $way5, 'Capricieux', 'Capricieuse', 0, 0, $book);
        $this->fixtureObject($repo, 83, $way5, 'Inconstant', 'Inconstante', 0, 0, $book);
        $this->fixtureObject($repo, 84, $way5, 'Inconséquent', 'Inconséquente', 0, 0, $book);
        $this->fixtureObject($repo, 85, $way5, 'Immoral', 'Immorale', 0, 0, $book);
        $this->fixtureObject($repo, 86, $way5, 'Doute', 'Doute', 0, 0, $book);
        $this->fixtureObject($repo, 87, $way5, 'Traître', 'Traitresse', 0, 0, $book);
        $this->fixtureObject($repo, 88, $way2, 'Opportuniste', 'Opportuniste', 0, 0, $book);
        $this->fixtureObject($repo, 89, $way1, 'Téméraire', 'Téméraire', 0, 1, $book);
        $this->fixtureObject($repo, 90, $way1, 'Persévérant', 'Persévérante', 1, 1, $book);
        $this->fixtureObject($repo, 91, $way5, 'Peu fiable', 'Peu fiable', 0, 0, $book);
        $this->fixtureObject($repo, 92, $way2, 'Austère', 'Austère', 0, 0, $book);
        $this->fixtureObject($repo, 93, $way4, 'Austère', 'Austère', 0, 1, $book);
        $this->fixtureObject($repo, 94, $way1, 'Distrait', 'Distraite', 0, 0, $book);

        $this->manager->flush();
    }

    public function fixtureObject(EntityRepository $repo, $id, $way, $name, $nameFemale, $isQuality, $major, $book): void
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
            $obj = new PersonalityTrait();
            $obj->setId($id)
                ->setName($name)
                ->setNameFemale($nameFemale)
                ->setWay($way)
                ->setBook($book)
                ->setMajor((bool) $major)
                ->setQuality((bool) $isQuality)
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
            $this->addReference('corahnrin-trait-'.$id, $obj);
        }
    }
}
