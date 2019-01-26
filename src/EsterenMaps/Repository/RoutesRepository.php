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

namespace EsterenMaps\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use EsterenMaps\Cache\CacheManager;
use EsterenMaps\Entity\Route;
use Orbitale\Component\DoctrineTools\EntityRepositoryHelperTrait;

class RoutesRepository extends ServiceEntityRepository
{
    use EntityRepositoryHelperTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Route::class);
    }

    public function findForApiByMap($mapId)
    {
        $query = $this->createQueryBuilder('route')
            ->select('
                route.id,
                route.name,
                route.description,
                route.coordinates,
                route.distance,
                route.forcedDistance as forced_distance,
                route.guarded,
                markerStart.id as marker_start,
                markerEnd.id as marker_end,
                routeFaction.id as faction,
                routeType.id as route_type
            ')
            ->leftJoin('route.map', 'map')
            ->leftJoin('route.markerStart', 'markerStart')
            ->leftJoin('route.markerEnd', 'markerEnd')
            ->leftJoin('route.faction', 'routeFaction')
            ->leftJoin('route.routeType', 'routeType')
            ->where('map.id = :id')
            ->setParameter('id', $mapId)
            ->indexBy('route', 'route.id')
            ->getQuery()
        ;

        $query->useResultCache(true, 3600, CacheManager::CACHE_PREFIX."api_map_$mapId\_routes");

        $routes = $query->getArrayResult();

        foreach ($routes as &$route) {
            $route['distance'] = $route['forced_distance'] ?: $route['distance'];
        }

        return $routes;
    }
}
