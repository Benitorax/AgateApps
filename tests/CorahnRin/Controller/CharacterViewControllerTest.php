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

namespace Tests\CorahnRin\Controller;

use CorahnRin\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\WebTestCase as PiersTestCase;

/**
 * @see \CorahnRin\Controller\CharacterViewController
 */
class CharacterViewControllerTest extends WebTestCase
{
    use PiersTestCase;

    /**
     * @see CharacterViewController::listAction
     */
    public function testList(): void
    {
        $client = $this->getClient('corahnrin.esteren.docker', [], ['ROLE_ADMIN']);

        $crawler = $client->request('GET', '/fr/characters/');

        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame(1, $crawler->filter('table.table.table-condensed')->count());
    }

    public function test list with invalid query must return 400(): void
    {
        $client = $this->getClient('corahnrin.esteren.docker', [], ['ROLE_ADMIN']);

        $client->request('GET', '/fr/characters/?order=undefined');

        static::assertSame(400, $client->getResponse()->getStatusCode());
    }

    /**
     * @see CharacterViewController::viewAction
     */
    public function testView404(): void
    {
        $client = $this->getClient('corahnrin.esteren.docker', [], ['ROLE_ADMIN']);

        $client->request('GET', '/fr/characters/9999999-aaaaaaaa');

        static::assertSame(404, $client->getResponse()->getStatusCode());
    }

    /**
     * @see CharacterViewController::viewAction
     */
    public function testView(): void
    {
        $client = $this->getClient('corahnrin.esteren.docker', [], ['ROLE_ADMIN']);

        /**
         * @var Character|null
         */
        $char = $client->getContainer()->get('doctrine')->getRepository(Character::class)->find(608);

        if (!$char) {
            static::markTestIncomplete('No character available in the database to test the route.');
        }

        $crawler = $client->request('GET', '/fr/characters/'.$char->getId().'-'.$char->getNameSlug());

        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame(1, $crawler->filter('h2.char-name')->count());
    }
}
