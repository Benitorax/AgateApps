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

class AssetsControllerTest extends WebTestCase
{
    use PiersTestCase;

    public function provideLocales()
    {
        yield 'fr' => ['fr'];
        yield 'en' => ['en'];
    }

    /**
     * @dataProvider provideLocales
     */
    public function testValidLocaleUrlWithNoCache(string $locale)
    {
        $client = $this->getClient('www.studio-agate.dev', ['debug' => true]);

        $client->request('GET', "/$locale/js/translations");
        $response = $client->getResponse();

        static::assertNotNull($response);
        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame('application/javascript', $response->headers->get('Content-type'));

        $content = $response->getContent();

        static::assertContains("window['LeafletDrawTranslations'] = ", $content);
        static::assertContains('const CONFIRM_DELETE = ', $content);
    }

    /**
     * @dataProvider provideLocalesAndHeaders
     */
    public function testValidLocaleUrlWithCache(string $locale, array $headers)
    {
        $client = $this->getClient('www.studio-agate.dev', ['debug' => false]);

        $versionCode = $client->getKernel()->getContainer()->getParameter('version_code');
        $versionDate = $client->getKernel()->getContainer()->getParameter('version_date');

        $server = [];
        if (in_array('etag', $headers, true)) {
            $server['HTTP_IF_NONE_MATCH'] = '"'.sha1('js'.$locale.$versionCode).'"';
        }
        if (in_array('last_modified', $headers, true)) {
            $modifiedSince = new \DateTime($versionDate);
            $modifiedSince->setTimezone(new \DateTimeZone('UTC'));
            $server['HTTP_IF_MODIFIED_SINCE'] = $modifiedSince->format('D, d M Y H:i:s').' GMT';
        }

        $client->request(
            'GET',
            "/$locale/js/translations",
            [],
            [],
            $server
        );

        $response = $client->getResponse();

        static::assertNotNull($response);
        static::assertSame(304, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider provideLocalesAndHeaders
     */
    public function testValidLocaleUrlWithInvalidCache(string $locale, array $headers)
    {
        $client = $this->getClient('www.studio-agate.dev', ['debug' => false]);

        $server = [];
        if (in_array('etag', $headers, true)) {
            $server['HTTP_IF_NONE_MATCH'] = '"'.sha1(uniqid('testcache', true)).'"';
        }
        if (in_array('last_modified', $headers, true)) {
            $modifiedSince = new \DateTime('+10 day');
            $modifiedSince->setTimezone(new \DateTimeZone('UTC'));
            $server['HTTP_IF_MODIFIED_SINCE'] = $modifiedSince->format('D, d M Y H:i:s').' GMT';
        }

        $client->request('GET', "/$locale/js/translations", [], [], $server);

        $response = $client->getResponse();

        $versionCode = $client->getKernel()->getContainer()->getParameter('version_code');
        $versionDate = $client->getKernel()->getContainer()->getParameter('version_date');

        static::assertNotNull($response);
        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertEquals('"'.sha1('js'.$locale.$versionCode).'"', $response->getEtag());
        static::assertEquals((new \DateTime($versionDate))->format('D, d M Y H:i:s'), $response->getLastModified()->format('D, d M Y H:i:s'));
    }

    public function provideLocalesAndHeaders()
    {
        yield ['fr', ['etag']];
        yield ['fr', ['last_modified']]; // FIXME
        yield ['fr', ['etag', 'last_modified']];
        yield ['en', ['etag']];
        yield ['en', ['last_modified']]; // FIXME
        yield ['en', ['etag', 'last_modified']];
    }
}
