<?php

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
use EsterenMaps\Entity\RouteType;

class RoutesTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RouteType::class);
    }

    /**
     * @return RouteType[]
     */
    public function findNotInIds(array $ids)
    {
        $qb = $this->createQueryBuilder('rt');

        if (\count($ids)) {
            $qb
                ->where('rt.id NOT IN (:ids)')
                ->setParameter(':ids', $ids)
            ;
        }

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }

    public function findForApi()
    {
        $query = $this->createQueryBuilder('route_type')
            ->select('
                route_type.id,
                route_type.name,
                route_type.description,
                route_type.color
            ')
            ->indexBy('route_type', 'route_type.id')
            ->getQuery()
        ;

        $query->useResultCache(true, 3600, CacheManager::CACHE_PREFIX.'api_routes_types');

        return $query->getArrayResult();
    }
}
