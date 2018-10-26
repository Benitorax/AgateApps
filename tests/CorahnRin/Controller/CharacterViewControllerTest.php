<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\CorahnRin\Controller;

use CorahnRin\Entity\Characters;
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
    public function testList()
    {
        $client = $this->getClient('corahnrin.esteren.docker', [], ['ROLE_ADMIN']);

        $crawler = $client->request('GET', '/fr/characters/');

        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame(1, $crawler->filter('table.table.table-condensed')->count());
    }

    /**
     * @see CharacterViewController::viewAction
     */
    public function testView404()
    {
        $client = $this->getClient('corahnrin.esteren.docker', [], ['ROLE_ADMIN']);

        $client->request('GET', '/fr/characters/9999999-aaaaaaaa');

        static::assertSame(404, $client->getResponse()->getStatusCode());
    }

    /**
     * @see CharacterViewController::viewAction
     */
    public function testView()
    {
        $client = $this->getClient('corahnrin.esteren.docker', [], ['ROLE_ADMIN']);

        /**
         * @var Characters|null
         */
        $char = $client->getContainer()->get('doctrine')->getRepository(Characters::class)->find(608);

        if (!$char) {
            static::markTestIncomplete('No character available in the database to test the route.');
        }

        $crawler = $client->request('GET', '/fr/characters/'.$char->getId().'-'.$char->getNameSlug());

        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame(1, $crawler->filter('h2.char-name')->count());
    }
}
