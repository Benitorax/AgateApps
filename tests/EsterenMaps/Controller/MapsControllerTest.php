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
use Subscription\Repository\SubscriptionRepository;
use User\Repository\UserRepository;

class MapsControllerTest extends WebTestCase
{
    use PiersTestCase;

    public function test index()
    {
        $client = $this->getClient('maps.esteren.docker');

        $crawler = $client->request('GET', '/fr/');

        static::assertSame(200, $client->getResponse()->getStatusCode());

        $article = $crawler->filter('.maps-list article');
        static::assertGreaterThanOrEqual(1, $article->count(), $crawler->filter('title')->text());

        $link = $article->filter('a')->link();

        static::assertInstanceOf(Link::class, $link);
        static::assertSame('Voir la carte', \trim($link->getNode()->textContent));
        static::assertSame('http://maps.esteren.docker/fr/map-tri-kazel', \trim($link->getUri()));
    }

    public function test view while not logged in should trigger authentication()
    {
        $client = $this->getClient('maps.esteren.docker');

        $client->request('GET', '/fr/map-tri-kazel');
        $res = $client->getResponse();

        static::assertSame(401, $res->getStatusCode());
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

        $user = self::$container->get(UserRepository::class)->findByUsernameOrEmail('pierstoval');

        static::assertNotNull($user);
        static::setToken($client, $user);

        $client->request('GET', '/fr/map-tri-kazel');
        $res = $client->getResponse();

        static::assertSame(403, $res->getStatusCode());
    }

    public function test view while connected is accessible for admin()
    {
        $client = $this->getClient('maps.esteren.docker');

        $user = self::$container->get(UserRepository::class)->findByUsernameOrEmail('pierstoval');

        static::assertNotNull($user);
        $user->addRole('ROLE_ADMIN');
        static::setToken($client, $user, $user->getRoles());

        $crawler = $client->request('GET', '/fr/map-tri-kazel');
        $res = $client->getResponse();

        static::assertSame(200, $res->getStatusCode());
        static::assertCount(1, $crawler->filter('#map_wrapper'), 'Map link does not redirect to map view, or map view is broken');
    }

    public function test view while connected is accessible for legacy subscriber user()
    {
        $client = $this->getClient('maps.esteren.docker');

        $user = self::$container->get(UserRepository::class)->findByUsernameOrEmail('pierstoval');

        static::assertNotNull($user);
        $user->addRole('ROLE_MAPS_VIEW');
        static::setToken($client, $user, $user->getRoles());

        $crawler = $client->request('GET', '/fr/map-tri-kazel');
        $res = $client->getResponse();

        static::assertSame(200, $res->getStatusCode());
        static::assertCount(1, $crawler->filter('#map_wrapper'), 'Map link does not redirect to map view, or map view is broken');
    }

    public function test view while connected is accessible for user with active subscription()
    {
        $client = $this->getClient('maps.esteren.docker');

        $user = self::$container->get(UserRepository::class)->findByUsernameOrEmail('map-subscribed');
        $subscriptions = self::$container->get(SubscriptionRepository::class)->getUserActiveSubscriptions($user);

        static::assertNotNull($user);
        static::setToken($client, $user, $user->getRoles());
        static::assertCount(1, $subscriptions);
        static::assertSame('subscription.esteren_maps', $subscriptions[0]->getType());

        $crawler = $client->request('GET', '/fr/map-tri-kazel');
        $res = $client->getResponse();

        static::assertSame(200, $res->getStatusCode());
        static::assertCount(1, $crawler->filter('#map_wrapper'), 'Map link does not redirect to map view, or map view is broken');
    }
}
