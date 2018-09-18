<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\WebTestCase as PiersTestCase;

class StaticUrlsTest extends WebTestCase
{
    use PiersTestCase;

    /**
     * @dataProvider provide root data
     */
    public function test root redirects with locale(string $browserLocale, string $host): void
    {
        $client = $this->getClient($host, [], ['ROLE_ADMIN']);

        $client->request('GET', '/', [], [], [
            'HTTP_ACCEPT_LANGUAGE' => [$browserLocale],
        ]);

        static::assertSame(301, $client->getResponse()->getStatusCode());
        static::assertTrue($client->getResponse()->isRedirect("/$browserLocale/"));
    }

    /**
     * @dataProvider provide root data
     */
    public function test root with slash redirects with locale(string $locale, string $host): void
    {
        $client = $this->getClient($host, [], ['ROLE_ADMIN']);

        $client->request('GET', "/$locale", [], [], [
            'HTTP_ACCEPT_LANGUAGE' => [$locale],
        ]);

        static::assertSame(301, $client->getResponse()->getStatusCode());
        static::assertTrue($client->getResponse()->isRedirect("/$locale/"));
    }

    public function provide root data()
    {
        yield 0 => ['fr', 'portal.esteren.docker'];
        yield 1 => ['fr', 'maps.esteren.docker'];
        yield 2 => ['fr', 'corahnrin.esteren.docker'];
        yield 3 => ['fr', 'games.esteren.docker'];
        yield 4 => ['fr', 'api.esteren.docker'];
        yield 5 => ['fr', 'back.esteren.docker'];
        yield 6 => ['fr', 'www.studio-agate.docker'];
        yield 7 => ['fr', 'stats.studio-agate.docker'];
        yield 8 => ['fr', 'www.dragons-rpg.docker'];
        yield 9 => ['fr', 'www.vermine2047.docker'];
        yield 10 => ['en', 'portal.esteren.docker'];
        yield 11 => ['en', 'maps.esteren.docker'];
        yield 12 => ['en', 'corahnrin.esteren.docker'];
        yield 13 => ['en', 'games.esteren.docker'];
        yield 14 => ['en', 'api.esteren.docker'];
        yield 15 => ['en', 'back.esteren.docker'];
        yield 16 => ['en', 'www.studio-agate.docker'];
        yield 17 => ['en', 'stats.studio-agate.docker'];
        yield 18 => ['en', 'www.dragons-rpg.docker'];
        yield 19 => ['en', 'www.vermine2047.docker'];
    }

    /**
     * @dataProvider provide test urls
     */
    public function test urls(
        string $domainName,
        string $url,
        ?string $expectedRouteName,
        int $expectedStatusCode = 200,
        string $expectedRedirectUrlOrTitleContent = '',
        string $cssSelectorToCheck = '#content h1'
    ): void {
        $client = $this->getClient($domainName);

        $crawler = $client->request('GET', $url, [], [], [
            'HTTP_ACCEPT_LANGUAGE' => ['fr'],
        ]);

        /** @var Request $req */
        $req = $client->getRequest();

        /** @var Response $res */
        $res = $client->getResponse();

        $this->assertSame($expectedRouteName, $req->attributes->get('_route'), 'Unexpected route name.');
        $this->assertSame($expectedStatusCode, $res->getStatusCode(), 'Unexpected status code.');

        if ($expectedRedirectUrlOrTitleContent) {
            /* @see \Symfony\Component\HttpFoundation\Response::isRedirect() */
            if (\in_array($expectedStatusCode, [201, 301, 302, 303, 307, 308], true)) {
                $message = \sprintf(
                    'Unexpected redirect url. Expected "%s", got "%s".',
                    $expectedRedirectUrlOrTitleContent, $res->headers->get('Location')
                );
                $this->assertTrue($res->isRedirect($expectedRedirectUrlOrTitleContent), $message);
            } else {
                $title = $crawler->filter($cssSelectorToCheck);
                $this->assertNotNull($title, 'No node found for the CSS selector.');
                $this->assertSame($expectedRedirectUrlOrTitleContent, \trim($title->text()));
            }
        }
    }

    public function provide test urls(): ?\Generator
    {
        // Studio Agate
        yield 0 => ['www.studio-agate.docker', '/fr/', 'agate_portal_home', 200, 'Bienvenue sur le nouveau portail du Studio Agate'];
        yield 1 => ['www.studio-agate.docker', '/en/', 'agate_portal_home', 200, 'Welcome to the new Studio Agate portal'];
        yield 2 => ['www.studio-agate.docker', '/fr/team', 'agate_team', 200, 'L\'Équipe du studio Agate'];
        yield 3 => ['www.studio-agate.docker', '/en/team', 'agate_team', 200, 'The Studio Agate team'];
        yield 4 => ['www.studio-agate.docker', '/fr/legal', 'legal_mentions', 200, 'Mentions légales', '#content h2'];
        yield 5 => ['www.studio-agate.docker', '/en/legal', 'legal_mentions', 404];

        // Vermine portal
        yield 6 => ['www.vermine2047.docker', '/fr/', 'vermine_portal_home', 200, 'Vermine 2047', 'title'];
        yield 7 => ['www.vermine2047.docker', '/en/', 'vermine_portal_home', 200, 'Vermine 2047', 'title'];

        // Esteren portal
        yield 8 => ['portal.esteren.docker', '/fr/', 'esteren_portal_home', 200, 'Bienvenue sur le nouveau portail des Ombres d\'Esteren'];
        yield 9 => ['portal.esteren.docker', '/en/', 'esteren_portal_home', 200, 'Welcome to the new Shadows of Esteren\'s portal!'];
        yield 10 => ['portal.esteren.docker', '/fr/feond-beer', 'esteren_portal_feond_beer', 200, 'La bière du Féond', 'title'];
        yield 11 => ['portal.esteren.docker', '/en/feond-beer', 'esteren_portal_feond_beer', 200, 'La bière du Féond', 'title'];

        // Corahn-Rin
        yield 12 => ['corahnrin.esteren.docker', '/fr/', 'corahn_rin_home', 200, 'Corahn-Rin, le générateur de personnage pour Les Ombres d\'Esteren', 'title'];
        yield 13 => ['corahnrin.esteren.docker', '/en/', 'corahn_rin_home', 200, 'Corahn-Rin, the character manager for Shadows of Esteren', 'title'];

        // Dragons
        yield 14 => ['www.dragons-rpg.docker', '/fr/', 'dragons_home', 200, 'Bienvenue sur le nouveau portail du jeu de rôle Dragons'];
        yield 15 => ['www.dragons-rpg.docker', '/en/', 'dragons_home', 200, 'Welcome to Dragons RPG portal'];
    }
}
