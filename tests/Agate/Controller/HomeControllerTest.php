<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\AgateController;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\WebTestCase as PiersTestCase;

class HomeControllerTest extends WebTestCase
{
    use PiersTestCase;

    public function testRootRedirectsToIndex()
    {
        $client = $this->getClient('www.studio-agate.dev');

        $client->request('GET', '/');
        $res = $client->getResponse();

        static::assertSame(301, $res->getStatusCode());
        static::assertSame('http://www.studio-agate.dev/fr/', $res->headers->get('Location'));
    }

    public function testIndexWithoutSlashRedirectsWithASlash()
    {
        $client = $this->getClient('www.studio-agate.dev');

        $client->request('GET', '/fr');
        $res = $client->getResponse();

        static::assertSame(301, $res->getStatusCode());
        static::assertSame('http://www.studio-agate.dev/fr/', $res->headers->get('Location'));
    }

    public function testIndexWithFrenchHomepage()
    {
        $client = $this->getClient('www.studio-agate.dev');

        $crawler = $client->request('GET', '/fr/');

        // Ensures that portal homepage is managed in a controller and not in the CMS
        static::assertSame('agate_portal_home', $client->getRequest()->attributes->get('_route'));

        static::assertSame(200, $client->getResponse()->getStatusCode(), trim($crawler->filter('title')->text()));

        // Check <h1> content only, this will be our "regression point" for homepage (now that it's static and no more in the CMS)
        static::assertSame('Bienvenue sur le nouveau portail du Studio Agate', trim($crawler->filter('#content h1')->text()));
    }

    public function testIndexWithEnglishHomepage()
    {
        $client = $this->getClient('www.studio-agate.dev');

        $client->request('GET', '/en/');

        // Ensures that portal homepage is managed in a controller and not in the CMS
        static::assertSame(302, $client->getResponse()->getStatusCode());
        static::assertTrue($client->getResponse()->isRedirect('/fr/'));
    }

    public function testFrenchTeamPage()
    {
        $client = $this->getClient('www.studio-agate.dev');

        $crawler = $client->request('GET', '/fr/team');

        // Ensures that portal homepage is managed in a controller and not in the CMS
        static::assertSame('agate_team', $client->getRequest()->attributes->get('_route'));

        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Check <h1> content only, this will be our "regression point" for homepage (now that it's static and no more in the CMS)
        static::assertSame('L\'Équipe du studio Agate', trim($crawler->filter('#content h1')->text()));
    }

    public function testEnglishTeamPage()
    {
        $client = $this->getClient('www.studio-agate.dev');

        $crawler = $client->request('GET', '/en/team');

        // Ensures that portal homepage is managed in a controller and not in the CMS
        static::assertSame('agate_team', $client->getRequest()->attributes->get('_route'));

        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Check <h1> content only, this will be our "regression point" for homepage (now that it's static and no more in the CMS)
        static::assertSame('The Studio Agate team', trim($crawler->filter('#content h1')->text()));
    }
}