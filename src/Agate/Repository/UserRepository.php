<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Agate\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Orbitale\Component\DoctrineTools\EntityRepositoryHelperTrait;
use Agate\Entity\User;
use Agate\Util\CanonicalizerTrait;

/**
 * @method User findOneBy(array $criteria, array $orderBy = null)
 */
class UserRepository extends ServiceEntityRepository
{
    use EntityRepositoryHelperTrait;
    use CanonicalizerTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByUsernameOrEmail(string $usernameOrEmail): ?User
    {
        return $this->createQueryBuilder('user')
            ->where('user.usernameCanonical = :usernameOrEmail')
            ->orWhere('user.emailCanonical = :usernameOrEmail')
            ->setParameter('usernameOrEmail', $this->canonicalize($usernameOrEmail))
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByEmail($email): ?User
    {
        return $this->findOneBy(['emailCanonical' => $this->canonicalize($email)]);
    }

    public function findOneByConfirmationToken($token): ?User
    {
        return $this->findOneBy(['confirmationToken' => $token]);
    }
}
