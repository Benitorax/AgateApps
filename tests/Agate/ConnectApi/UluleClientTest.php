<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Agate\ConnectApi;

use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Tests\WebTestCase as PiersTestCase;
use User\ConnectApi\UluleClient;
use User\Entity\User;

class UluleClientTest extends WebTestCase
{
    use PiersTestCase;

    protected static $clientResults;

    public function testUluleClientProjects()
    {
        $user = new User();
        $user->setUluleId(1);
        $user->setUluleUsername('user');
        $user->setUluleApiToken('token');

        $client = $this->createUluleClient();

        $ululeProjects = $client->getUserProjects($user);

        static::assertSame(static::$clientResults['projects'], $ululeProjects);
    }

    private static function initClientResults()
    {
        if (static::$clientResults['getUserProjects']) {
            return;
        }

        static::$clientResults = [
            'projects' => \json_decode(\file_get_contents(__DIR__.'/ulule_responses/projects.json'), true),
            'orders' => \json_decode(\file_get_contents(__DIR__.'/ulule_responses/orders.json'), true),
        ];
    }

    private function createUluleClient()
    {
        static::initClientResults();

        $cache = new ArrayAdapter();
        $item = $cache->getItem('ulule_projects.user_1');
        $item->set(static::$clientResults['projects']);
        $cache->save($item);

        return new UluleClient(new NullLogger(), $cache);
    }
}
