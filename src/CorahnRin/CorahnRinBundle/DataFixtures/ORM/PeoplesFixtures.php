<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\CorahnRinBundle\DataFixtures\ORM;

use CorahnRin\CorahnRinBundle\Entity\Peoples;
use CorahnRin\CorahnRinBundle\Entity\Books;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class PeoplesFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 2;
    }

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $repo = $this->manager->getRepository('CorahnRinBundle:Peoples');

        /** @var Books $book */
        $book = $this->getReference('corahnrin-book-2');

        $this->fixtureObject($repo, 1, 'Tri-Kazel', 'Les Tri-Kazeliens constituent la très grande majorité de la population de la péninsule. La plupart d\'entre eux conservent une stature assez robuste héritée des Osags mais peuvent aussi avoir des traits d\'autres peuples. Les Tri-Kazeliens sont issus de siècles de mélanges entre toutes les cultures ayant un jour ou l\'autre foulé le sol de la péninsule.<br /><br />De par cette origine, le PJ connaît un dialecte local ; il faut donc préciser de quel pays et région il est originaire.', $book);
        $this->fixtureObject($repo, 2, 'Tarish', 'D\'origine inconnue, le peuple Tarish forme une minorité nomade qui parcourt depuis des décennies les terres de la péninsule. Il est aussi appelé "peuple de l\'ouest" car la légende veut qu\'il soit arrivé par l\'Océan Furieux. Les Tarishs se distinguent des Tri-Kazeliens par des pommettes hautes, le nez plutôt aquilin et les yeux souvent clairs. Beaucoup d\'entre eux deviennent des saltimbanques, des mystiques ou des artisans.<br />La culture Tarish, même si elle est diluée aujourd\'hui, conserve encore une base importante : c\'est un peuple nomade habitué aux longs périples et leur langue n\'a pas disparu, bien qu\'aucun étranger ne l\'ait jamais apprise.', $book);
        $this->fixtureObject($repo, 3, 'Osag', 'Habitués à ne compter que sur eux-mêmes, les Osags forment un peuple rude. Généralement dotés d\'une carrure imposante, ils sont les descendants directs des clans traditionnels de la péninsule. La civilisation péninsulaire a beaucoup évolué depuis l\'avènement des Trois Royaumes, mais certains clans sont restés fidèles aux traditions ancestrales et n\'ont pas pris part à ces changements. Repliés sur leur mode de vie clanique, les Osags ne se sont pas métissés avec les autres peuples et ont gardé de nombreuses caractéristiques de leurs ancêtres. Les Osags font de grands guerriers et comptent parmi eux les plus célèbres Demorthèn.<br /><br />Leur langue a elle aussi survécu au passage des siècles. Les mots "feondas", "C\'maogh", "Dàmàthair" - pour ne citer qu\'eux - viennent tous de ce que les Tri-Kazeliens nomment la langue ancienne, mais qui est toujours utilisée par les Osags.', $book);
        $this->fixtureObject($repo, 4, 'Continent', 'Les hommes et les femmes du Continent sont souvent plus minces et plus élancés que les natifs de Tri-Kazel. Leur visage aura tendance à être plus fin mais avec des traits parfois taillés à la serpe. Un PJ choisissant ce peuple ne sera pas natif du Continent, mais plutôt le descendant direct d\'au moins un parent Continental. Si les origines Continentales du PJ sont davantage diluées, on estime qu\'il fait partie du peuple de Tri-Kazel.<br /><br />En fonction du passé de la famille du PJ et de son niveau d\'intégration dans la société tri-kazelienne, il pourrait avoir appris leur langue d\'origine Continentale ou bien un patois de la péninsule, au choix du PJ.', $book);

        $this->manager->flush();
    }

    public function fixtureObject(EntityRepository $repo, $id, $name, $description, $book)
    {
        $obj       = null;
        $newObject = false;
        $addRef    = false;
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
        if ($newObject === true) {
            $obj = new Peoples();
            $obj->setId($id)
                ->setName($name)
                ->setDescription($description)
                ->setBook($book)
            ;
            if ($id) {
                /** @var ClassMetadata $metadata */
                $metadata = $this->manager->getClassMetaData(get_class($obj));
                $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            }
            $this->manager->persist($obj);
            $addRef = true;
        }
        if ($addRef === true && $obj) {
            $this->addReference('corahnrin-people-'.$id, $obj);
        }
    }
}
