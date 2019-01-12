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

use CorahnRin\Entity\MentalDisorder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MentalDisorderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MentalDisorder::class);
    }

    /**
     * @return MentalDisorder[]
     */
    public function findWithWays()
    {
        return $this->createQueryBuilder('disorder')
            ->leftJoin('disorder.ways', 'way')
                ->addSelect('way')
            ->indexBy('disorder', 'disorder.id')
            ->getQuery()->getResult()
        ;
    }
}
