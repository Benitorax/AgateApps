<?php

namespace Tests;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

abstract class WebTestCase extends BaseWebTestCase
{
    /**
     * Rewrite database before each test so we have a clean one.
     */
    public function resetDatabase()
    {
        if (defined('DATABASE_TEST_FILE') && defined('DATABASE_REFERENCE_FILE')) {
            $fs = new Filesystem();
            $fs->copy(DATABASE_TEST_FILE, DATABASE_REFERENCE_FILE);
        } else {
            throw new \InvalidArgumentException('"DATABASE_TEST_FILE" and "DATABASE_REFERENCE_FILE" should be defined to reset database.');
        }
    }

    /**
     * @param string       $host
     * @param array        $kernelOptions
     * @param array|string $tokenRoles
     *
     * @return Client
     */
    protected function getClient($host = null, array $kernelOptions = [], $tokenRoles = null)
    {
        $server = [];
        if ($host) {
            $server['HTTP_HOST'] = $host;
        }
        $client = static::createClient($kernelOptions, $server);
        // Disable reboot, allows client to be reused for other requests.
        $client->disableReboot();

        if ($tokenRoles) {
            static::setToken($client, 'user', is_array($tokenRoles) ? $tokenRoles : [$tokenRoles]);
        }

        return $client;
    }

    /**
     * @param Client       $client
     * @param string       $userName
     * @param array|string $roles
     */
    protected static function setToken(Client $client, $userName = 'user', array $roles = ['ROLE_USER'])
    {
        $session = $client->getContainer()->get('session');

        if (is_string($roles)) {
            $roles = [$roles];
        }

        $firewall = 'main';
        $token    = new UsernamePasswordToken($userName, null, $firewall, $roles);
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }

}
