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
use EsterenMaps\Entity\Faction;

class FactionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Faction::class);
    }

    public function findForApi()
    {
        $query = $this->createQueryBuilder('faction')
            ->select('
                faction.id,
                faction.name,
                faction.description
            ')
            ->indexBy('faction', 'faction.id')
            ->getQuery()
        ;

        $query->useResultCache(true, 3600, CacheManager::CACHE_PREFIX.'api_factions');

        return $query->getArrayResult();
    }
}
