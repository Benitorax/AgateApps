<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Repository;

use CorahnRin\Entity\CombatArt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

final class CombatArtsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CombatArt::class);
    }

    /**
     * @return CombatArt[]
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
