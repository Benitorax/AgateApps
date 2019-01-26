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

use CorahnRin\Data\DomainsData;
use CorahnRin\Entity\Book;
use CorahnRin\Entity\Discipline;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class DisciplinesFixtures extends AbstractFixture implements OrderedFixtureInterface, ORMFixtureInterface
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

        $repo = $this->manager->getRepository(\CorahnRin\Entity\Discipline::class);

        /** @var Book $book */
        $book = $this->getReference('corahnrin-book-2');

        $this->fixtureObject($repo, 1, 'Acrobaties', '', Discipline::RANK_PROFESSIONAL, [DomainsData::FEATS['title']], $book);
        $this->fixtureObject($repo, 2, 'Agriculture', '', Discipline::RANK_PROFESSIONAL, [DomainsData::NATURAL_ENVIRONMENT['title']], $book);
        $this->fixtureObject($repo, 3, 'Arbalètes', '', Discipline::RANK_PROFESSIONAL, [DomainsData::SHOOTING_AND_THROWING['title']], $book);
        $this->fixtureObject($repo, 4, 'Architecture', '', Discipline::RANK_PROFESSIONAL, [DomainsData::SCIENCE['title']], $book);
        $this->fixtureObject($repo, 5, 'Arcs', '', Discipline::RANK_PROFESSIONAL, [DomainsData::SHOOTING_AND_THROWING['title']], $book);
        $this->fixtureObject($repo, 6, 'Armes contondantes', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CLOSE_COMBAT['title']], $book);
        $this->fixtureObject($repo, 7, 'Armes de jet', '', Discipline::RANK_PROFESSIONAL, [DomainsData::SHOOTING_AND_THROWING['title']], $book);
        $this->fixtureObject($repo, 8, 'Artefact de combat', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CLOSE_COMBAT['title'], DomainsData::OCCULTISM['title'], DomainsData::SCIENCE['title'], DomainsData::SHOOTING_AND_THROWING['title']], $book);
        $this->fixtureObject($repo, 9, 'Attelage', '', Discipline::RANK_PROFESSIONAL, [DomainsData::TRAVEL['title']], $book);
        $this->fixtureObject($repo, 10, 'Baratin', '', Discipline::RANK_PROFESSIONAL, [DomainsData::RELATION['title']], $book);
        $this->fixtureObject($repo, 11, 'Bâtons', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CLOSE_COMBAT['title']], $book);
        $this->fixtureObject($repo, 12, 'Bijouterie', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CRAFT['title']], $book);
        $this->fixtureObject($repo, 13, 'Botanique', '', Discipline::RANK_PROFESSIONAL, [DomainsData::SCIENCE['title']], $book);
        $this->fixtureObject($repo, 14, 'Camouflage', '', Discipline::RANK_PROFESSIONAL, [DomainsData::STEALTH['title']], $book);
        $this->fixtureObject($repo, 15, 'Cartographie', '', Discipline::RANK_PROFESSIONAL, [DomainsData::TRAVEL['title']], $book);
        $this->fixtureObject($repo, 16, 'Chant', '', Discipline::RANK_PROFESSIONAL, [DomainsData::PERFORMANCE['title']], $book);
        $this->fixtureObject($repo, 17, 'Charme', '', Discipline::RANK_PROFESSIONAL, [DomainsData::RELATION['title']], $book);
        $this->fixtureObject($repo, 18, 'Chemins de traverse (Varigal)', '', Discipline::RANK_PROFESSIONAL, [DomainsData::TRAVEL['title']], $book);
        $this->fixtureObject($repo, 19, 'Combat à mains nues', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CLOSE_COMBAT['title']], $book);
        $this->fixtureObject($repo, 20, 'Combat aveugle', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CLOSE_COMBAT['title']], $book);
        $this->fixtureObject($repo, 21, 'Comédie', '', Discipline::RANK_PROFESSIONAL, [DomainsData::PERFORMANCE['title']], $book);
        $this->fixtureObject($repo, 22, 'Commandement', '', Discipline::RANK_PROFESSIONAL, [DomainsData::RELATION['title']], $book);
        $this->fixtureObject($repo, 23, 'Concentration', '', Discipline::RANK_PROFESSIONAL, [DomainsData::DEMORTHEN_MYSTERIES['title'], DomainsData::PRAYER['title']], $book);
        $this->fixtureObject($repo, 24, 'Confection', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CRAFT['title']], $book);
        $this->fixtureObject($repo, 25, 'Conn. troubles mentaux', '', Discipline::RANK_PROFESSIONAL, [DomainsData::SCIENCE['title']], $book);
        $this->fixtureObject($repo, 26, 'Conn. d\'une faction', '', Discipline::RANK_PROFESSIONAL, [DomainsData::RELATION['title']], $book);
        $this->fixtureObject($repo, 27, 'Conn. des Flux', '', Discipline::RANK_PROFESSIONAL, [DomainsData::MAGIENCE['title']], $book);
        $this->fixtureObject($repo, 28, 'Conn. du Temple', '', Discipline::RANK_PROFESSIONAL, [DomainsData::PRAYER['title']], $book);
        $this->fixtureObject($repo, 29, 'Course', '', Discipline::RANK_PROFESSIONAL, [DomainsData::FEATS['title']], $book);
        $this->fixtureObject($repo, 30, 'Cuisine', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CRAFT['title']], $book);
        $this->fixtureObject($repo, 31, 'Danse', '', Discipline::RANK_PROFESSIONAL, [DomainsData::PERFORMANCE['title']], $book);
        $this->fixtureObject($repo, 32, 'Diplomatie', '', Discipline::RANK_PROFESSIONAL, [DomainsData::RELATION['title']], $book);
        $this->fixtureObject($repo, 33, 'Distillation', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CRAFT['title']], $book);
        $this->fixtureObject($repo, 34, 'Doctrine du Temple', '', Discipline::RANK_PROFESSIONAL, [DomainsData::ERUDITION['title']], $book);
        $this->fixtureObject($repo, 35, 'Dressage d\'animaux', '', Discipline::RANK_PROFESSIONAL, [DomainsData::NATURAL_ENVIRONMENT['title']], $book);
        $this->fixtureObject($repo, 36, 'Endurance', '', Discipline::RANK_PROFESSIONAL, [DomainsData::FEATS['title']], $book);
        $this->fixtureObject($repo, 37, 'Épées', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CLOSE_COMBAT['title']], $book);
        $this->fixtureObject($repo, 38, 'Équitation', '', Discipline::RANK_PROFESSIONAL, [DomainsData::TRAVEL['title']], $book);
        $this->fixtureObject($repo, 39, 'Escalade', '', Discipline::RANK_PROFESSIONAL, [DomainsData::FEATS['title']], $book);
        $this->fixtureObject($repo, 40, 'Ésotérisme', '', Discipline::RANK_PROFESSIONAL, [DomainsData::OCCULTISM['title']], $book);
        $this->fixtureObject($repo, 41, 'Étiquette d\'un milieu social', '', Discipline::RANK_PROFESSIONAL, [DomainsData::RELATION['title']], $book);
        $this->fixtureObject($repo, 42, 'Évaluation', '', Discipline::RANK_PROFESSIONAL, [DomainsData::PERCEPTION['title']], $book);
        $this->fixtureObject($repo, 43, 'Évasion', '', Discipline::RANK_PROFESSIONAL, [DomainsData::FEATS['title']], $book);
        $this->fixtureObject($repo, 44, 'Extraction de Flux', '', Discipline::RANK_PROFESSIONAL, [DomainsData::MAGIENCE['title']], $book);
        $this->fixtureObject($repo, 45, 'Extraction minière', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CRAFT['title']], $book);
        $this->fixtureObject($repo, 46, 'Faune et flore', '', Discipline::RANK_PROFESSIONAL, [DomainsData::NATURAL_ENVIRONMENT['title']], $book);
        $this->fixtureObject($repo, 47, 'Forge', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CRAFT['title']], $book);
        $this->fixtureObject($repo, 48, 'Furtivité', '', Discipline::RANK_PROFESSIONAL, [DomainsData::STEALTH['title']], $book);
        $this->fixtureObject($repo, 49, 'Géographie', '', Discipline::RANK_PROFESSIONAL, [DomainsData::ERUDITION['title']], $book);
        $this->fixtureObject($repo, 50, 'Géologie', '', Discipline::RANK_PROFESSIONAL, [DomainsData::SCIENCE['title']], $book);
        $this->fixtureObject($repo, 51, 'Haches', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CLOSE_COMBAT['title']], $book);
        $this->fixtureObject($repo, 52, 'Héraldique', '', Discipline::RANK_PROFESSIONAL, [DomainsData::ERUDITION['title']], $book);
        $this->fixtureObject($repo, 53, 'Herboristerie', '', Discipline::RANK_PROFESSIONAL, [DomainsData::DEMORTHEN_MYSTERIES['title'], DomainsData::ERUDITION['title']], $book);
        $this->fixtureObject($repo, 54, 'Histoire', '', Discipline::RANK_PROFESSIONAL, [DomainsData::ERUDITION['title']], $book);
        $this->fixtureObject($repo, 55, 'Hypnose', '', Discipline::RANK_PROFESSIONAL, [DomainsData::OCCULTISM['title']], $book);
        $this->fixtureObject($repo, 56, 'Ingénierie', '', Discipline::RANK_PROFESSIONAL, [DomainsData::SCIENCE['title']], $book);
        $this->fixtureObject($repo, 57, 'Instrument de musique', '', Discipline::RANK_PROFESSIONAL, [DomainsData::PERFORMANCE['title']], $book);
        $this->fixtureObject($repo, 58, 'Interprétation des rêves', '', Discipline::RANK_PROFESSIONAL, [DomainsData::OCCULTISM['title']], $book);
        $this->fixtureObject($repo, 59, 'Intimidation', '', Discipline::RANK_PROFESSIONAL, [DomainsData::RELATION['title']], $book);
        $this->fixtureObject($repo, 60, 'Jeux', '', Discipline::RANK_PROFESSIONAL, [DomainsData::PERFORMANCE['title']], $book);
        $this->fixtureObject($repo, 61, 'Jonglage', '', Discipline::RANK_PROFESSIONAL, [DomainsData::PERFORMANCE['title']], $book);
        $this->fixtureObject($repo, 62, 'Lames courtes', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CLOSE_COMBAT['title']], $book);
        $this->fixtureObject($repo, 63, 'Langue ancienne', '', Discipline::RANK_PROFESSIONAL, [DomainsData::DEMORTHEN_MYSTERIES['title']], $book);
        $this->fixtureObject($repo, 64, 'Langues', '', Discipline::RANK_PROFESSIONAL, [DomainsData::ERUDITION['title']], $book);
        $this->fixtureObject($repo, 65, 'Lecture sur les lèvres', '', Discipline::RANK_PROFESSIONAL, [DomainsData::PERCEPTION['title']], $book);
        $this->fixtureObject($repo, 66, 'Machinerie magientiste', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CRAFT['title'], DomainsData::SCIENCE['title']], $book);
        $this->fixtureObject($repo, 67, 'Maroquinerie', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CRAFT['title']], $book);
        $this->fixtureObject($repo, 68, 'Mécanique', '', Discipline::RANK_PROFESSIONAL, [DomainsData::SCIENCE['title']], $book);
        $this->fixtureObject($repo, 69, 'Médecine', '', Discipline::RANK_PROFESSIONAL, [DomainsData::MAGIENCE['title'], DomainsData::SCIENCE['title']], $book);
        $this->fixtureObject($repo, 70, 'Médecine traditionnelle', '', Discipline::RANK_PROFESSIONAL, [DomainsData::DEMORTHEN_MYSTERIES['title']], $book);
        $this->fixtureObject($repo, 71, 'Méditation', '', Discipline::RANK_PROFESSIONAL, [DomainsData::DEMORTHEN_MYSTERIES['title']], $book);
        $this->fixtureObject($repo, 72, 'Menuiserie', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CRAFT['title']], $book);
        $this->fixtureObject($repo, 73, 'Mimétisme', '', Discipline::RANK_PROFESSIONAL, [DomainsData::STEALTH['title']], $book);
        $this->fixtureObject($repo, 74, 'Miracles', '', Discipline::RANK_PROFESSIONAL, [DomainsData::PRAYER['title']], $book);
        $this->fixtureObject($repo, 75, 'Natation', '', Discipline::RANK_PROFESSIONAL, [DomainsData::FEATS['title']], $book);
        $this->fixtureObject($repo, 76, 'Navigation', '', Discipline::RANK_PROFESSIONAL, [DomainsData::TRAVEL['title']], $book);
        $this->fixtureObject($repo, 77, 'Observation', '', Discipline::RANK_PROFESSIONAL, [DomainsData::PERCEPTION['title']], $book);
        $this->fixtureObject($repo, 78, 'Orientation', '', Discipline::RANK_PROFESSIONAL, [DomainsData::NATURAL_ENVIRONMENT['title'], DomainsData::PERCEPTION['title'], DomainsData::TRAVEL['title']], $book);
        $this->fixtureObject($repo, 79, 'Outil magientiste', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CRAFT['title'], DomainsData::OCCULTISM['title'], DomainsData::SCIENCE['title']], $book);
        $this->fixtureObject($repo, 80, 'Peinture', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CRAFT['title']], $book);
        $this->fixtureObject($repo, 81, 'Persuasion', '', Discipline::RANK_PROFESSIONAL, [DomainsData::RELATION['title']], $book);
        $this->fixtureObject($repo, 82, 'Phénomènes mentaux', '', Discipline::RANK_PROFESSIONAL, [DomainsData::OCCULTISM['title']], $book);
        $this->fixtureObject($repo, 83, 'Pistage', '', Discipline::RANK_PROFESSIONAL, [DomainsData::NATURAL_ENVIRONMENT['title']], $book);
        $this->fixtureObject($repo, 84, 'Politique', '', Discipline::RANK_PROFESSIONAL, [DomainsData::ERUDITION['title']], $book);
        $this->fixtureObject($repo, 85, 'Poterie', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CRAFT['title']], $book);
        $this->fixtureObject($repo, 86, 'Premiers soins', '', Discipline::RANK_PROFESSIONAL, [DomainsData::NATURAL_ENVIRONMENT['title']], $book);
        $this->fixtureObject($repo, 87, 'Principes magientistes', '', Discipline::RANK_PROFESSIONAL, [DomainsData::ERUDITION['title']], $book);
        $this->fixtureObject($repo, 88, 'Raffinage de Flux', '', Discipline::RANK_PROFESSIONAL, [DomainsData::MAGIENCE['title']], $book);
        $this->fixtureObject($repo, 89, 'Recueillement', '', Discipline::RANK_PROFESSIONAL, [DomainsData::PRAYER['title']], $book);
        $this->fixtureObject($repo, 90, 'Réparation d\'artefacts', '', Discipline::RANK_PROFESSIONAL, [DomainsData::MAGIENCE['title'], DomainsData::SCIENCE['title']], $book);
        $this->fixtureObject($repo, 91, 'Savoirs demorthèn', '', Discipline::RANK_PROFESSIONAL, [DomainsData::DEMORTHEN_MYSTERIES['title']], $book);
        $this->fixtureObject($repo, 92, 'Sculpture', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CRAFT['title']], $book);
        $this->fixtureObject($repo, 93, 'Sens aiguisés', '', Discipline::RANK_PROFESSIONAL, [DomainsData::PERCEPTION['title']], $book);
        $this->fixtureObject($repo, 94, 'Serrurerie', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CRAFT['title']], $book);
        $this->fixtureObject($repo, 95, 'Sigil Rann', '', Discipline::RANK_PROFESSIONAL, [DomainsData::DEMORTHEN_MYSTERIES['title']], $book);
        $this->fixtureObject($repo, 96, 'Signes (Varigal)', '', Discipline::RANK_PROFESSIONAL, [DomainsData::TRAVEL['title']], $book);
        $this->fixtureObject($repo, 97, 'Spiritualité', '', Discipline::RANK_PROFESSIONAL, [DomainsData::DEMORTHEN_MYSTERIES['title'], DomainsData::PRAYER['title'], DomainsData::SCIENCE['title']], $book);
        $this->fixtureObject($repo, 98, 'Survie', '', Discipline::RANK_PROFESSIONAL, [DomainsData::NATURAL_ENVIRONMENT['title']], $book);
        $this->fixtureObject($repo, 99, 'Traditions demorthèn', '', Discipline::RANK_PROFESSIONAL, [DomainsData::ERUDITION['title']], $book);
        $this->fixtureObject($repo, 100, 'Utilisation d\'artefacts', '', Discipline::RANK_PROFESSIONAL, [DomainsData::MAGIENCE['title']], $book);
        $this->fixtureObject($repo, 101, 'Ventriloquie', '', Discipline::RANK_PROFESSIONAL, [DomainsData::PERFORMANCE['title']], $book);
        $this->fixtureObject($repo, 102, 'Vigilance', '', Discipline::RANK_PROFESSIONAL, [DomainsData::PERCEPTION['title']], $book);
        $this->fixtureObject($repo, 103, 'Vol à la tire', '', Discipline::RANK_PROFESSIONAL, [DomainsData::STEALTH['title']], $book);
        $this->fixtureObject($repo, 104, 'Zoologie', '', Discipline::RANK_PROFESSIONAL, [DomainsData::SCIENCE['title']], $book);
        $this->fixtureObject($repo, 105, 'Armes d\'hast', '', Discipline::RANK_PROFESSIONAL, [DomainsData::CLOSE_COMBAT['title']], $book);
        $this->fixtureObject($repo, 106, 'Astronomie', '', Discipline::RANK_PROFESSIONAL, [DomainsData::ERUDITION['title']], $book);
        $this->fixtureObject($repo, 107, 'Légendes', '', Discipline::RANK_PROFESSIONAL, [DomainsData::ERUDITION['title']], $book);
        $this->fixtureObject($repo, 108, 'Travail de force', '', Discipline::RANK_PROFESSIONAL, [DomainsData::FEATS['title']], $book);
        $this->fixtureObject($repo, 109, 'Traitement de l\'esprit', '', Discipline::RANK_PROFESSIONAL, [DomainsData::SCIENCE['title']], $book);

        $this->manager->flush();
    }

    public function fixtureObject(EntityRepository $repo, ?int $id, string $name, string $description, string $rank, array $domains, Book $book)
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
            $obj = new Discipline();
            $obj->setId($id);
            $obj->setName($name);
            $obj->setDescription($description);
            $obj->setRank($rank);
            $obj->setBook($book);
            $obj->setDomains($domains);
            if ($id) {
                /** @var ClassMetadata $metadata */
                $metadata = $this->manager->getClassMetadata(\get_class($obj));
                $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            }
            $this->manager->persist($obj);
            $addRef = true;
        }
        if (true === $addRef && $obj) {
            $this->addReference('corahnrin-discipline-'.$id, $obj);
        }
    }
}
