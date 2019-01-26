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
use EsterenMaps\Entity\Marker;
use Orbitale\Component\DoctrineTools\EntityRepositoryHelperTrait;

/**
 * MarkersRepository.
 */
class MarkersRepository extends ServiceEntityRepository
{
    use EntityRepositoryHelperTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Marker::class);
    }

    public function findForApiByMap($mapId)
    {
        $query = $this->createQueryBuilder('marker')
            ->select('
                marker.id,
                marker.name,
                marker.description,
                marker.latitude,
                marker.longitude,
                markerType.id as marker_type,
                markerFaction.id as faction
            ')
            ->leftJoin('marker.map', 'map')
            ->leftJoin('marker.markerType', 'markerType')
            ->leftJoin('marker.faction', 'markerFaction')
            ->where('map.id = :id')
            ->setParameter('id', $mapId)
            ->indexBy('marker', 'marker.id')
            ->getQuery()
        ;

        $query->useResultCache(true, 3600, CacheManager::CACHE_PREFIX."api_map_$mapId\_markers");

        return $query->getArrayResult();
    }
}
