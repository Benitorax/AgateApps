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

use CorahnRin\Entity\Discipline;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DisciplinesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Discipline::class);
    }

    /**
     * @param int[] $domainsIds
     *
     * @return Discipline[]
     */
    public function findAllByDomains(array $domainsIds)
    {
        return $this->createQueryBuilder('discipline', 'discipline.id')
            ->from($this->_entityName, 'disciplines', 'disciplines.id')
            ->where('discipline.domains in (:ids)')// TODO: Fix this part
            ->setParameter('ids', $domainsIds)
            ->orderBy('disciplines.name', 'asc')
            ->getQuery()->getResult()
        ;
    }
}
