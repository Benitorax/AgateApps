<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use EsterenMaps\Cache\CacheManager;
use EsterenMaps\Entity\ZoneType;

class ZonesTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ZoneType::class);
    }

    public function findForApi()
    {
        $query = $this->createQueryBuilder('zone_type')
            ->select('
                zone_type.id,
                zone_type.name,
                zone_type.description,
                zone_type.color,
                zoneParent.id as parent_id
            ')
            ->leftJoin('zone_type.parent', 'zoneParent')
            ->indexBy('zone_type', 'zone_type.id')
            ->getQuery()
        ;

        $query->useResultCache(true, 3600, CacheManager::CACHE_PREFIX.'api_zones_types');

        return $query->getArrayResult();
    }
}
