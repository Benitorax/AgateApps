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

use CorahnRin\Entity\Armor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ArmorsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Armor::class);
    }

    /**
     * @return Armor[]
     */
    public function findAllSortedByName()
    {
        return $this->_em->createQueryBuilder()
            ->select('armor')
            ->from($this->_entityName, 'armor', 'armor.id')
            ->orderBy('armor.name', 'asc')
            ->getQuery()->getResult()
        ;
    }
}
