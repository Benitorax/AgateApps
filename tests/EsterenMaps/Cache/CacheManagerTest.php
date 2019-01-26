<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\EsterenMaps\Cache;

use DataFixtures\RoutesFixtures;
use Doctrine\ORM\EntityManagerInterface;
use EsterenMaps\Entity\Route;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\DataCollector\LoggerDataCollector;
use Symfony\Component\VarDumper\Cloner\Data;
use Tests\WebTestCase as PiersTestCase;

class CacheManagerTest extends WebTestCase
{
    use PiersTestCase;

    public function test updating route should clear maps cache()
    {
        static::resetDatabase();

        static::bootKernel();
        $em = static::$container->get(EntityManagerInterface::class);

        /** @see RoutesFixtures::getSimplerCanvasRoutes for route 701 details */
        $routeId = 700;

        $coordinates = '[{"lat":0,"lng":0},{"lat":10,"lng":10},{"lat":5,"lng":5},{"lat":0,"lng":5},{"lat":0,"lng":10}]';

        $client = $this->getClient('back.esteren.docker', [], ['ROLE_ADMIN']);
        $client->enableProfiler();

        /** @var Route $route */
        $route = $em->find(Route::class, $routeId);
        static::assertNotNull($route);
        static::assertSame('[{"lat":0,"lng":0},{"lat":0,"lng":10}]', $route->getCoordinates());
        static::assertSame(50, $route->getDistance());

        /*
         * Here we only change coordinates for greater distance.
         */
        $client->request('POST', "/fr/api/routes/$routeId", [], [], [], \json_encode(
            [
                'map' => $route->getMap()->getId(),
                'name' => $route->getName(),
                'coordinates' => $coordinates,
                'description' => $route->getDescription(),
                'routeType' => $route->getRouteType()->getId(),
                'markerStart' => $route->getMarkerStart()->getId(),
                'markerEnd' => $route->getMarkerEnd()->getId(),
                'faction' => $route->getFaction() ? $route->getFaction()->getId() : null,
                'guarded' => $route->isGuarded(),
                'forcedDistance' => $route->getForcedDistance(),
            ]
        ));
        $em = self::$container->get(EntityManagerInterface::class);

        $profile = $client->getProfile();

        $em->clear();
        $route = $em->find(Route::class, $routeId);
        static::assertNotNull($route);
        static::assertSame($coordinates, $route->getCoordinates());
        static::assertSame(156, $route->getDistance());

        /** @var LoggerDataCollector $loggerDataCollector */
        $loggerDataCollector = $profile->getCollector('logger');
        $loggerDataCollector->lateCollect();

        /*
         * Retrieve log item from collector.
         * This is the way we know whether CacheManager was triggered or not.
         */
        $logToCheck = null;
        foreach ($loggerDataCollector->getLogs() as $log) {
            if ($log instanceof Data) {
                $log = $log->getValue(true);
            }
            if (($log['message'] ?? '') === 'Clearing doctrine cache') {
                if ($logToCheck) {
                    static::fail('Seems that cache clear log was fired twice or more.');
                }

                $logToCheck = $log;
            }
        }

        static::assertNotNull($logToCheck, 'Log was not fired, it means that cache manager was not triggered.');
    }
}
