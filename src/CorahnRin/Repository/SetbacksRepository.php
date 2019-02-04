<?php

declare(strict_types=1);

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Repository;

use CorahnRin\Entity\Setback;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Orbitale\Component\DoctrineTools\EntityRepositoryHelperTrait;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Setback|null findOneBy(array $criteria, array $orderBy = null)
 * @method Setback[]    findBy(array $criteria, array $orderBy = null)
 * @method Setback|null find($id, $lockMode = null, $lockVersion = null)
 */
class SetbacksRepository extends ServiceEntityRepository
{
    use EntityRepositoryHelperTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Setback::class);
    }

    /**
     * @param int[]|Setback[] $setbacks
     *
     * @return Setback[]
     */
    public function findWithDisabledAdvantages(array $setbacks): array
    {
        return $this->createQueryBuilder('setback')
            ->leftJoin('setback.disabledAdvantages', 'disabledAdvantages')
            ->addSelect('disabledAdvantages')
            ->where('setback.id IN (:setbacks)')
            ->setParameter('setbacks', $setbacks)
            ->getQuery()
            ->getResult()
        ;
    }
}
