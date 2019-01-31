<?php

namespace Agate\Repository;

use Agate\Entity\PortalElement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class PortalElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PortalElement::class);
    }

    public function findForHomepage(string $locale, string $portal): ?PortalElement
    {
        return $this->findOneBy([
            'locale' => $locale,
            'portal' => $portal,
        ]);
    }
}
