<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Repository;

use CorahnRin\Entity\CombatArts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

final class CombatArtsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CombatArts::class);
    }

    /**
     * @return CombatArts[]
     */
    public function findAllSortedByName()
    {
        return $this->createQueryBuilder('combat_art', 'combat_art.id')
            ->from($this->_entityName, 'combat_arts', 'combat_arts.id')
            ->orderBy('combat_arts.name', 'asc')
            ->getQuery()->getResult()
        ;
    }
}
