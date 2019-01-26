<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Agate\Controller;

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
        $client = $this->getClient('www.studio-agate.docker');

        $client->request('GET', "/$locale/js/translations");
        $response = $client->getResponse();

        static::assertNotNull($response);
        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame('application/javascript', $response->headers->get('Content-type'));

        $content = $response->getContent();

        static::assertContains("window['LeafletDrawTranslations'] = ", $content);
        static::assertContains('var CONFIRM_DELETE = ', $content);
    }
}
