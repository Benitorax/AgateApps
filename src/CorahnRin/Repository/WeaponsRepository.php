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

use CorahnRin\Entity\Weapon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

final class WeaponsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Weapon::class);
    }

    /**
     * @return Weapon[]
     */
    public function findAllSortedByName()
    {
        return $this->_em->createQueryBuilder()
            ->select('weapon')
            ->from($this->_entityName, 'weapon', 'weapon.id')
            ->orderBy('weapon.name', 'asc')
            ->getQuery()->getResult()
        ;
    }
}
