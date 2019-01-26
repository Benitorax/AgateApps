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

use CorahnRin\Data\DomainsData;
use CorahnRin\Entity\GeoEnvironment;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class GeoEnvironmentsFixtures extends AbstractFixture implements OrderedFixtureInterface, ORMFixtureInterface
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
    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        /** @var EntityRepository $repo */
        $repo = $this->manager->getRepository(GeoEnvironment::class);

        $book = $this->getReference('corahnrin-book-2');

        $this->fixtureObject($repo, 1, DomainsData::NATURAL_ENVIRONMENT['title'], 'Rural', 'Votre personnage est issu d\'une campagne ou d\'un lieu relativement isolé.', $book);
        $this->fixtureObject($repo, 2, DomainsData::RELATION['title'], 'Urbain', 'Votre personnage a vécu longtemps dans une ville, suffisamment pour qu\'il ait adopté les codes de la ville dans son mode de vie.', $book);

        $this->manager->flush();
    }

    public function fixtureObject(EntityRepository $repo, $id, $domain, $name, $description, $book): void
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
            $obj = new GeoEnvironment($id, $name, $description, $domain);
            $obj->setBook($book);
            if ($id) {
                /** @var ClassMetadata $metadata */
                $metadata = $this->manager->getClassMetadata(\get_class($obj));
                $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            }
            $this->manager->persist($obj);
            $addRef = true;
        }
        if (true === $addRef && $obj) {
            $this->addReference('corahnrin-geo-environment-'.$id, $obj);
        }
    }
}
