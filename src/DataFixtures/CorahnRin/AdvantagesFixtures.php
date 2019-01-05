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

use CorahnRin\Data\DomainsData;
use CorahnRin\Entity\Avantages;
use CorahnRin\Entity\Book;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class AdvantagesFixtures extends AbstractFixture implements OrderedFixtureInterface, ORMFixtureInterface
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

        $repo = $this->manager->getRepository(Avantages::class);

        /** @var Book $book */
        $book = $this->getReference('corahnrin-book-2');

        $this->fixtureObject($repo, 1, 'advantages.group.ally', 'Allié isolé', 'Allié isolé', 20, 'Un allié dans un village, prévôt, marchand, artisan...', 0, [], 0, 0, $book, 'advantages.indication.ally_isolated');
        $this->fixtureObject($repo, 2, 'advantages.group.ally', 'Allié mentor', 'Allié mentor', 40, 'Un mentor ou un professeur qui vous donne un bonus de +1 dans un Domaine', 0, [], 0, 0, $book, 'advantages.indication.ally_mentor');
        $this->fixtureObject($repo, 3, 'advantages.group.ally', 'Allié influent', 'Allié influent', 50, 'Un important homme politique, chef de guilde ou de clan, qui a un pouvoir important dans tout le pays', 0, [], 0, 0, $book, 'advantages.indication.ally_influent');
        $this->fixtureObject($repo, 4, 'advantages.group.financial_ease', 'Aisance financière 1', 'Aisance financière 1', 10, '+20 daols d\'azur à la création du personnage', 0, ['money_azure_20'], 0, 0, $book);
        $this->fixtureObject($repo, 5, 'advantages.group.financial_ease', 'Aisance financière 2', 'Aisance financière 2', 20, '+50 daols d\'azur à la création du personnage', 0, ['money_azure_50'], 0, 0, $book);
        $this->fixtureObject($repo, 6, 'advantages.group.financial_ease', 'Aisance financière 3', 'Aisance financière 3', 30, '+10 daols de givre à la création du personnage', 0, ['money_frost_10'], 0, 0, $book);
        $this->fixtureObject($repo, 7, 'advantages.group.financial_ease', 'Aisance financière 4', 'Aisance financière 4', 40, '+50 daols de givre à la création du personnage', 0, ['money_frost_50'], 0, 0, $book);
        $this->fixtureObject($repo, 8, 'advantages.group.financial_ease', 'Aisance financière 5', 'Aisance financière 5', 50, '+100 daols de givre à la création du personnage', 0, ['money_frost_100'], 0, 0, $book);
        $this->fixtureObject($repo, 9, null, 'Beau', 'Belle', 30, '+1 aux jets de Relation et Représentation', 1, [DomainsData::RELATION['title'], DomainsData::PERFORMANCE['title']], 0, 0, $book);
        $this->fixtureObject($repo, 10, null, 'Bonne santé', 'Bonne santé', 40, '+1 case d\'état de santé, +1 aux jets de Vigueur face à la maladie et aux poisons', 1, ['health'], 0, 0, $book);
        $this->fixtureObject($repo, 11, null, 'Bonne vue', 'Bonne vue', 30, '+1 aux jets de Perception concernant la vue et Tir et Lancer', 1, [DomainsData::PERCEPTION['title'], DomainsData::SHOOTING_AND_THROWING['title']], 0, 0, $book);
        $this->fixtureObject($repo, 12, null, 'Charismatique', 'Charismatique', 30, '+1 aux jets de Relation et Représentation', 1, [DomainsData::RELATION['title'], DomainsData::PERFORMANCE['title']], 0, 0, $book);
        $this->fixtureObject($repo, 13, null, 'Endurant', 'Endurante', 30, '+1 au score de Vigueur et aux jets de Prouesses concernant l\'endurance', 1, ['stamina', DomainsData::FEATS['title']], 0, 0, $book);
        $this->fixtureObject($repo, 14, null, 'Esprit solide', 'Esprit solide', 30, '+1 au score de Résistance Mentale', 1, ['mental_resistance'], 0, 0, $book);
        $this->fixtureObject($repo, 15, null, 'Fort', 'Forte', 40, '+1 aux jets de Prouesses concernant la force, Combat au Contact, Tir & lancer', 1, [DomainsData::CLOSE_COMBAT['title'], DomainsData::FEATS['title'], DomainsData::SHOOTING_AND_THROWING['title']], 0, 0, $book);
        $this->fixtureObject($repo, 16, null, 'Intuitif', 'Intuitive', 40, '+1 aux jets de Mystères Demorthèn, Voyage et Relation', 1, [DomainsData::DEMORTHEN_MYSTERIES['title'], DomainsData::RELATION['title'], DomainsData::TRAVEL['title']], 0, 0, $book);
        $this->fixtureObject($repo, 17, null, 'Leste', 'Leste', 40, '+1 au score de Défense, et aux jets de Discrétion et de Prouesses concernant l\'agilité', 1, ['defense', DomainsData::STEALTH['title'], DomainsData::FEATS['title']], 0, 0, $book);
        $this->fixtureObject($repo, 18, null, 'Ouïe fine', 'Ouïe fine', 20, '+1 aux jets de Perception auditive', 1, [DomainsData::PERFORMANCE['title']], 0, 0, $book);
        $this->fixtureObject($repo, 19, null, 'Rapide', 'Rapide', 20, '+1 au score de Rapidité', 1, ['speed'], 0, 0, $book);
        $this->fixtureObject($repo, 20, null, 'Vif d\'esprit', 'Vive d\'esprit', 40, '+1 aux jets de Science, Magience et Occultisme', 1, [DomainsData::MAGIENCE['title'], DomainsData::OCCULTISM['title'], DomainsData::SCIENCE['title']], 0, 0, $book);
        $this->fixtureObject($repo, 21, null, 'Chanceux', 'Chanceuse', 30, '+1 aux jets de Chance', 1, ['luck'], 0, 0, $book);
        $this->fixtureObject($repo, 22, null, 'Instinct de survie', 'Instinct de survie', 30, '+1 point de Survie', 1, ['survival'], 0, 0, $book);
        $this->fixtureObject($repo, 23, null, 'Lettré', 'Lettrée', 20, 'Le personnage sait lire et écrire, et choisit un bonus de +1 au choix : Erudition, Magience, Science ou Occultisme', 0, [DomainsData::ERUDITION['title'], DomainsData::SCIENCE['title'], DomainsData::MAGIENCE['title'], DomainsData::OCCULTISM['title']], 0, 0, $book, 'advantages.indication.scholar', Avantages::INDICATION_TYPE_SINGLE_CHOICE);
        $this->fixtureObject($repo, 29, null, 'Nez fin', 'Nez fin', 10, '+1 aux jets de Perception concernant l\'odorat', 1, [DomainsData::PERCEPTION['title']], 0, 0, $book);
        $this->fixtureObject($repo, 30, null, 'Palais fin', 'Palais fin', 10, '+1 aux jets de Perception concernant le goût', 1, [DomainsData::PERCEPTION['title']], 0, 0, $book);
        $this->fixtureObject($repo, 31, null, 'Boiteux', 'Boiteuse', 30, '-1 en Rapidité et en Défense', 0, ['defense', 'speed'], 1, 0, $book);
        $this->fixtureObject($repo, 32, null, 'Dépendance', 'Dépendance', 20, '-1 en Vigueur, et une addiction (tabac, alcool, drogue...)', 0, ['stamina'], 1, 0, $book, 'advantages.indication.dependence');
        $this->fixtureObject($repo, 33, null, 'Douillet', 'Douillette', 20, '-1 au score de Vigueur et aux jets de Prouesses concernant l\'endurance', 1, ['stamina', DomainsData::FEATS['title']], 1, 0, $book);
        $this->fixtureObject($repo, 34, null, 'Ennemi', 'Ennemi', 30, 'Une personne en veut au PJ et fera tout pour lui nuire', 0, [], 1, 0, $book, 'advantages.indication.enemy');
        $this->fixtureObject($repo, 35, null, 'Esprit faible', 'Esprit faible', 20, '-1 au score de Résistance Mentale', 1, ['mental_resistance'], 1, 0, $book);
        $this->fixtureObject($repo, 36, null, 'Faible', 'Faible', 30, '-1 aux jets de Prouesses concernant la force, Combat au Contact, Tir & lancer', 1, [DomainsData::CLOSE_COMBAT['title'], DomainsData::FEATS['title'], DomainsData::SHOOTING_AND_THROWING['title']], 1, 0, $book);
        $this->fixtureObject($repo, 37, null, 'Lent d\'esprit', 'Lente d\'esprit', 30, '-1 aux jets de Science, Magience et Occultisme', 1, [DomainsData::SCIENCE['title'], DomainsData::OCCULTISM['title'], DomainsData::MAGIENCE['title']], 1, 0, $book);
        $this->fixtureObject($repo, 38, null, 'Fragile', 'Fragile', 20, '-1 point de Survie', 1, ['survival'], 1, 0, $book);
        $this->fixtureObject($repo, 39, null, 'Obtus', 'Obtuse', 30, '-1 aux jets de Mystères Demorthèn, Voyage et Relation', 1, [DomainsData::DEMORTHEN_MYSTERIES['title'], DomainsData::TRAVEL['title'], DomainsData::RELATION['title']], 1, 0, $book);
        $this->fixtureObject($repo, 40, null, 'Laid', 'Laide', 20, '-1 aux jets de Relation et Représentation', 1, [DomainsData::RELATION['title'], DomainsData::PERFORMANCE['title']], 1, 0, $book);
        $this->fixtureObject($repo, 41, null, 'Lent', 'Lente', 10, '-1 au score de Rapidité', 1, ['speed'], 1, 0, $book);
        $this->fixtureObject($repo, 42, null, 'Mal entendant', 'Mal entendante', 20, '-1 aux jets de Perception auditive', 1, [DomainsData::PERCEPTION['title']], 1, 0, $book);
        $this->fixtureObject($repo, 43, null, 'Malchanceux', 'Malchanceuse', 10, '-1 aux jets de Chance', 1, ['luck'], 1, 0, $book);
        $this->fixtureObject($repo, 44, null, 'Maladif', 'Maladive', 30, '-1 case d\'état de santé, -1 point de Vigueur', 0, ['health', 'stamina'], 1, 0, $book);
        $this->fixtureObject($repo, 45, null, 'Maladroit', 'Maladroite', 30, '-1 au score de Défense, et aux jets de Discrétion et de Prouesses concernant l\'agilité', 1, ['defense', DomainsData::STEALTH['title'], DomainsData::FEATS['title']], 1, 0, $book);
        $this->fixtureObject($repo, 46, null, 'Myope', 'Myope', 20, '-1 aux jets de Perception concernant la vue et Tir et Lancer', 1, [DomainsData::PERCEPTION['title'], DomainsData::SHOOTING_AND_THROWING['title']], 1, 0, $book);
        $this->fixtureObject($repo, 47, null, 'Pauvre', 'Pauvre', 10, 'Le PJ ne disposera que du quart de la somme en Daols fournie à la création', 0, [], 1, 0, $book);
        $this->fixtureObject($repo, 48, null, 'Phobie', 'Phobie', 40, '+1 point de trauma, et souffre du désordre Phobie en plus de son désordre actuel', 0, ['trauma'], 1, 0, $book, 'advantages.indication.phobia');
        $this->fixtureObject($repo, 49, null, 'Timide', 'Timide', 10, '-1 aux jets de Relation et Représentation', 0, [DomainsData::RELATION['title'], DomainsData::PERFORMANCE['title']], 1, 0, $book);
        $this->fixtureObject($repo, 50, null, 'Traumatisme', 'Traumatisme', 10, '+1 point de trauma', 2, ['trauma'], 1, 0, $book);
        $this->fixtureObject($repo, 51, null, 'Anosmie', 'Anosmie', 5, '-1 aux jets de Perception concernant l\'odorat', 1, [DomainsData::PERCEPTION['title']], 1, 0, $book);
        $this->fixtureObject($repo, 52, null, 'Agueusie', 'Agueusie', 5, '-1 aux jets de Perception concernant le goût', 1, [DomainsData::PERCEPTION['title']], 1, 0, $book);

        $this->manager->flush();
    }

    public function fixtureObject(
        EntityRepository $repo,
        $id,
        $group,
        $name,
        $nameFemale,
        $xp,
        $description,
        $augmentation,
        array $bonusesFor,
        $isDesv,
        $isCombatArt,
        $book,
        ?string $requiresIndication = null,
        string $indicationType = Avantages::INDICATION_TYPE_SINGLE_VALUE
    ) {
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
            $obj = new Avantages();
            $obj->setId($id);
            $obj->setGroup($group);
            $obj->setName($name);
            $obj->setXp($xp);
            $obj->setNameFemale($nameFemale);
            $obj->setDescription($description);
            $obj->setAugmentationCount($augmentation);
            $obj->setDesv($isDesv);
            $obj->setCombatArt($isCombatArt);
            $obj->setBook($book);
            $obj->setBonusesFor($bonusesFor);
            $obj->setRequiresIndication($requiresIndication);
            $obj->setIndicationType($indicationType);

            if ($id) {
                /** @var ClassMetadata $metadata */
                $metadata = $this->manager->getClassMetadata(\get_class($obj));
                $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            }
            $this->manager->persist($obj);
            $addRef = true;
        }
        if (true === $addRef && $obj) {
            $this->addReference('corahnrin-avantage-'.$id, $obj);
        }
    }
}
