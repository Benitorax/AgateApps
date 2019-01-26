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

use CorahnRin\Entity\GeoEnvironment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Orbitale\Component\DoctrineTools\EntityRepositoryHelperTrait;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GeoEnvironmentsRepository extends ServiceEntityRepository
{
    use EntityRepositoryHelperTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GeoEnvironment::class);
    }
}
