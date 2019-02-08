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

namespace Tests\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\WebTestCase as PiersTestCase;

class DefaultEasyAdminTest extends WebTestCase
{
    use PiersTestCase;

    public function test index returns 200 when logged in as admin(): void
    {
        $client = $this->getClient('back.esteren.docker', [], 'ROLE_ADMIN');
        $crawler = $client->request('GET', '/fr/');

        static::assertSame(200, $client->getResponse()->getStatusCode(), $crawler->filter('title')->html());
        static::assertSame('EasyAdmin', $crawler->filter('meta[name="generator"]')->attr('content'));
        static::assertSame('', \trim($crawler->filter('#main')->text()));
    }

    /**
     * @dataProvider provide actions that need id
     */
    public function test actions that need id must throw a 404 exception(string $action): void
    {
        $client = $this->getClient('back.esteren.docker', [], 'ROLE_ADMIN');

        $crawler = $client->request('GET', "/fr/PortalElement/$action");

        static::assertSame(404, $client->getResponse()->getStatusCode(), $crawler->filter('title')->html());
        static::assertSame('An id must be specified for this action.', $crawler->filter('h1.exception-message')->text());
    }

    public function provide actions that need id(): \Generator
    {
        yield 'delete' => ['delete'];
        yield 'show' => ['show'];
        yield 'edit' => ['edit'];
    }
}
