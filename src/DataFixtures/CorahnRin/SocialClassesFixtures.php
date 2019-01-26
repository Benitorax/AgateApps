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
use CorahnRin\Entity\SocialClass;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class SocialClassesFixtures extends AbstractFixture implements OrderedFixtureInterface, ORMFixtureInterface
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
        return 4;
    }

    /**
     * Load data fixtures with the passed EntityManager.
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        /** @var EntityRepository $repo */
        $repo = $this->manager->getRepository(SocialClass::class);

        $this->fixtureObject($repo, 1, [DomainsData::NATURAL_ENVIRONMENT['title'], DomainsData::PERCEPTION['title'], DomainsData::FEATS['title'], DomainsData::TRAVEL['title']], 'Paysan', 'Les roturiers font partie de la majorité de la population. Vous avez vécu dans une famille paysanne, à l\'écart des villes et cités, sans pour autant les ignorer. Vous êtes plus proche de la nature.'."\n".'les Demorthèn font également partie de cette classe sociale.');
        $this->fixtureObject($repo, 2, [DomainsData::CRAFT['title'], DomainsData::RELATION['title'], DomainsData::SCIENCE['title'], DomainsData::ERUDITION['title']], 'Artisan', 'Les roturiers font partie de la majorité de la population. Votre famille était composée d\'un ou plusieurs artisans ou ouvriers, participant à la vie communale et familiale usant de ses talents manuels.');
        $this->fixtureObject($repo, 3, [DomainsData::CRAFT['title'], DomainsData::RELATION['title'], DomainsData::PERFORMANCE['title'], DomainsData::ERUDITION['title']], 'Bourgeois', 'Votre famille a su faire des affaires dans les villes, ou tient probablement un commerce célèbre dans votre région, ce qui vous permet de vivre confortablement au sein d\'une communauté familière.');
        $this->fixtureObject($repo, 4, [DomainsData::PRAYER['title'], DomainsData::RELATION['title'], DomainsData::TRAVEL['title'], DomainsData::ERUDITION['title']], 'Clergé', 'Votre famille a toujours respecté l\'Unique et ses représentants, et vous êtes issu d\'un milieu très pieux.'."\n".'Vous avez probablement la foi, vous aussi.');
        $this->fixtureObject($repo, 5, [DomainsData::CLOSE_COMBAT['title'], DomainsData::RELATION['title'], DomainsData::SCIENCE['title'], DomainsData::ERUDITION['title']], 'Noblesse', 'Vous portez peut-être un grand nom des affaires des grandes cités, ou avez grandi en ville. Néanmoins, votre famille est placée assez haut dans la noblesse pour vous permettre d\'avoir eu des enseignements particuliers.');

        $this->manager->flush();
    }

    public function fixtureObject(EntityRepository $repo, $id, $domains, $name, $description)
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
            $obj = new SocialClass();
            $obj->setId($id);
            $obj->setName($name);
            $obj->setDescription($description);
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
            $this->addReference('corahnrin-social-class-'.$id, $obj);
        }
    }
}
