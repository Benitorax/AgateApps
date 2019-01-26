<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DataFixtures;

use Agate\Entity\PortalElement;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Orbitale\Component\DoctrineTools\AbstractFixture;

final class PortalElementFixtures extends AbstractFixture implements ORMFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    protected function getEntityClass(): string
    {
        return PortalElement::class;
    }

    protected function setGeneratorBasedOnId(ClassMetadata $metadata, $id = null): void
    {
        $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_AUTO);
    }

    /**
     * {@inheritdoc}
     */
    protected function getObjects()
    {
        return [
            [
                'id' => 1,
                'portal' => 'esteren',
                'locale' => 'fr',
                'imageUrl' => 'maps/esteren_map.jpg',
                'title' => 'Portail Esteren',
                'subtitle' => 'sub',
                'buttonText' => 'button',
                'buttonLink' => '/',
            ],
            [
                'id' => 2,
                'portal' => 'esteren',
                'locale' => 'en',
                'imageUrl' => 'maps/esteren_map.jpg',
                'title' => 'Esteren Portal',
                'subtitle' => 'sub',
                'buttonText' => 'button',
                'buttonLink' => '/',
            ],
            [
                'id' => 3,
                'portal' => 'agate',
                'locale' => 'fr',
                'imageUrl' => 'maps/esteren_map.jpg',
                'title' => 'Portail Agate',
                'subtitle' => 'sub',
                'buttonText' => 'button',
                'buttonLink' => '/',
            ],
            [
                'id' => 4,
                'portal' => 'agate',
                'locale' => 'en',
                'imageUrl' => 'maps/esteren_map.jpg',
                'title' => 'Agate Portal',
                'subtitle' => 'sub',
                'buttonText' => 'button',
                'buttonLink' => '/',
            ],
            [
                'id' => 5,
                'portal' => 'dragons',
                'locale' => 'fr',
                'imageUrl' => 'maps/esteren_map.jpg',
                'title' => 'Portail Dragons',
                'subtitle' => 'sub',
                'buttonText' => 'button',
                'buttonLink' => '/',
            ],
            [
                'id' => 6,
                'portal' => 'dragons',
                'locale' => 'en',
                'imageUrl' => 'maps/esteren_map.jpg',
                'title' => 'Dragons Portal',
                'subtitle' => 'sub',
                'buttonText' => 'button',
                'buttonLink' => '/',
            ],
        ];
    }
}
