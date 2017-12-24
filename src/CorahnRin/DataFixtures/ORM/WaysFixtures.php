<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\DataFixtures\ORM;

use CorahnRin\Entity\Ways;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class WaysFixtures extends AbstractFixture implements OrderedFixtureInterface
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

        /** @var EntityRepository $repo */
        $repo = $this->manager->getRepository(\CorahnRin\Entity\Ways::class);

        $this->fixtureObject($repo, 1, 'com', 'Combativité', 'Passion', 'Cette Voie traduit la pugnacité, l\'énergie qui pousse à agir, la rage de vivre.');
        $this->fixtureObject($repo, 2, 'cre', 'Créativité', 'Subversion', 'La capacité à imaginer, à donner à sa vie un sens original, l\'inventivité, la débrouillardise.');
        $this->fixtureObject($repo, 3, 'emp', 'Empathie', 'Émotivité', 'Le lien qui relie un être humain à son environnement.
Par exemple, les Demorthèn se servent de leur Empathie pour communiquer avec la nature.
Au niveau relationnel, l\'Empathie désigne la faculté de ressentir les émotions d\'une autre personne.');
        $this->fixtureObject($repo, 4, 'rai', 'Raison', 'Doute', 'La rationnalisation, mais aussi la recherche et la réflexion. Elle traduit la capacité d\'apprentissage d\'un personnage, sa curiosité, etc.');
        $this->fixtureObject($repo, 5, 'ide', 'Idéal', 'Culpabilité', 'Généralement, un humain se raccroche à un idéal ou des convictions qui guident sa vie.
Certains se tournent vers la religion, d\'autres vers des préceptes de chevalerie, d\'autres encore suivent un code personnel.');

        $this->manager->flush();
    }

    public function fixtureObject(EntityRepository $repo, $id, $shortName, $name, $fault, $description)
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
            $obj = new Ways();
            $obj->setId($id)
                ->setName($name)
                ->setDescription($description)
                ->setShortName($shortName)
                ->setFault($fault)
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
            $this->addReference('corahnrin-way-'.$id, $obj);
        }
    }
}