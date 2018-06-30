<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\EsterenMaps\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Link;
use Tests\WebTestCase as PiersTestCase;
use User\Repository\UserRepository;

class MapsControllerTest extends WebTestCase
{
    use PiersTestCase;

    public function test root redirects to index()
    {
        $client = $this->getClient('maps.esteren.docker');

        $client->request('GET', '/');
        $res = $client->getResponse();

        static::assertSame(301, $res->getStatusCode());
        static::assertSame('http://maps.esteren.docker/fr/', $res->headers->get('Location'));
    }

    public function test index without slash redirects with a slash()
    {
        $client = $this->getClient('maps.esteren.docker');

        $client->request('GET', '/fr');
        $res = $client->getResponse();

        static::assertSame(301, $res->getStatusCode());
        static::assertSame('http://maps.esteren.docker/fr/', $res->headers->get('Location'));
    }

    public function test index()
    {
        $client = $this->getClient('maps.esteren.docker');

        static::setToken($client, 'map allowed', ['ROLE MAPS VIEW']);

        $crawler = $client->request('GET', '/fr/');

        static::assertSame(200, $client->getResponse()->getStatusCode());

        $article = $crawler->filter('.maps-list article');
        static::assertGreaterThanOrEqual(1, $article->count(), $crawler->filter('title')->text());

        $link = $article->filter('a')->link();

        static::assertInstanceOf(Link::class, $link);
        static::assertSame('Voir la carte', trim($link->getNode()->textContent));
        static::assertSame('http://maps.esteren.docker/fr/map-tri-kazel', trim($link->getUri()));
    }

    public function test view while not logged in()
    {
        $client = $this->getClient('maps.esteren.docker');

        $client->request('GET', '/fr/map-tri-kazel');
        $res = $client->getResponse();

        static::assertSame(302, $res->getStatusCode());
        static::assertSame('/fr/login', $res->headers->get('Location'));
    }

    public function test view when authenticated without permission()
    {
        $client = $this->getClient('maps.esteren.docker');

        static::setToken($client);

        $client->request('GET', '/fr/map-tri-kazel');
        $res = $client->getResponse();

        static::assertSame(403, $res->getStatusCode());
    }

    public function test view while connected is not accessible for classic user()
    {
        $client = $this->getClient('maps.esteren.docker');

        $user = $client->getContainer()->get(UserRepository::class)->findByUsernameOrEmail('pierstoval');

        static::assertNotNull($user);
        static::setToken($client, $user);

        $crawler = $client->request('GET', '/fr/map-tri-kazel');
        $res = $client->getResponse();

        static::assertSame(403, $res->getStatusCode());
    }

    public function test view while connected is accessible for backer user()
    {
        $client = $this->getClient('maps.esteren.docker');

        $user = $client->getContainer()->get(UserRepository::class)->findByUsernameOrEmail('pierstoval');

        static::assertNotNull($user);
        $user->addRole('ROLE_MAPS_VIEW');
        static::setToken($client, $user, $user->getRoles());

        $crawler = $client->request('GET', '/fr/map-tri-kazel');
        $res = $client->getResponse();

        static::assertSame(200, $res->getStatusCode());
        static::assertCount(1, $crawler->filter('#map_wrapper'), 'Map link does not redirect to map view, or map view is broken');
    }
}
