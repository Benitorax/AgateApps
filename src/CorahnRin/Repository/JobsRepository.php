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

use CorahnRin\Entity\Job;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class JobsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Job::class);
    }

    /**
     * Renvoie la liste des métiers triés par livre associé.
     *
     * @return Job[][]
     */
    public function findAllPerBook()
    {
        /** @var Job[] $jobs */
        $jobs = $this->findAll();

        $books = [];

        foreach ($jobs as $job) {
            $books[$job->getBook()->getId()][$job->getId()] = $job;
        }

        return $books;
    }
}
