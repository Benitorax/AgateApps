<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\EsterenMaps\Controller\PantherTests;

use PHPUnit\Framework\AssertionFailedError;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;
use Tests\WebTestCase as PiersTestCase;

class JSMapsControllerTest extends PantherTestCase
{
    use PiersTestCase;

    private static $oldEnv;

    public static function setUpBeforeClass()
    {
        static::$oldEnv = $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? \getenv('APP_ENV') ?: 'dev';

        \putenv('APP_ENV=panther');
        $_ENV['APP_ENV'] = 'panther';
        $_SERVER['APP_ENV'] = 'panther';
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        \putenv('APP_ENV='.static::$oldEnv);
        $_ENV['APP_ENV'] = static::$oldEnv;
        $_SERVER['APP_ENV'] = static::$oldEnv;

        static::$oldEnv = null;
    }

    public function login(Client $pantherClient, string $host, string $username, string $password): void
    {
        $crawler = $pantherClient->request('GET', "http://$host:9080/fr/login");

        $form = $crawler->filter('#form_login')->form();
        $form->get('_username_or_email')->setValue($username);
        $form->get('_password')->setValue($password);

        $pantherClient->submit($form);
    }

    public function __toString()
    {
        return $this->toString();
    }

    protected function screenshot(Client $client, string $suffix)
    {
        $normalizedMethod = \preg_replace(
            '~^tests_~i',
            '_',
            \str_replace(['\\', '::', ':'], '_', (string) $this)
        );

        $fileName = __DIR__.'/../../../../build/screenshots/'.$normalizedMethod.$suffix.'.png';

        $client->takeScreenshot($fileName);
    }

    /**
     * @legacy
     */
    public function testMapIndex()
    {
        try {
            $client = static::createPantherClient();

            $this->login($client, 'maps.esteren.docker', 'Pierstoval', 'admin');

            $this->screenshot($client, 'login_response');

            $crawler = $client->request('GET', 'http://maps.esteren.docker:9080/fr/map-tri-kazel');

            $this->screenshot($client, 'map_view');

            static::assertSame(200, $client->getInternalResponse()->getStatus());
            static::assertCount(1, $crawler->filter('#map_wrapper'), 'Map link does not redirect to map view, or map view is broken');
        } catch (\Exception $e) {
            if ($e instanceof AssertionFailedError) {
                throw $e;
            }

            $msg = '';

            $i = 0;
            do {
                $msg .= "\n#$i: ".$e->getMessage();
                $i++;
            } while ($e = $e->getPrevious());

            $this->markAsRisky();
            static::markTestSkipped(\sprintf('Panthère test returned error:%s', $msg));
        }
    }
}
