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

use CorahnRin\Entity\Character;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CharactersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Character::class);
    }

    public function findFetched(int $id): ?Character
    {
        return $this->_em
            ->createQueryBuilder()
            ->select('characters')
            ->from($this->_entityName, 'characters')
                ->leftJoin('characters.job', 'job')->addSelect('job')
                ->leftJoin('characters.ways', 'ways')->addSelect('ways')
                ->leftJoin('characters.people', 'people')->addSelect('people')
                ->leftJoin('characters.birthPlace', 'birthplace')->addSelect('birthplace')
                ->leftJoin('characters.armors', 'armors')->addSelect('armors')
                ->leftJoin('characters.weapons', 'weapons')->addSelect('weapons')
                ->leftJoin('characters.artifacts', 'artifacts')->addSelect('artifacts')
                ->leftJoin('characters.flux', 'flux')->addSelect('flux')
                ->leftJoin('characters.miracles', 'miracles')->addSelect('miracles')
                ->leftJoin('characters.domains', 'domains')->addSelect('domains')
                ->leftJoin('characters.disciplines', 'disciplines')->addSelect('disciplines')
                ->leftJoin('characters.avantages', 'avantages')->addSelect('avantages')
            ->where('characters.id = :id')
                ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param string $searchField Le champ dans lequel exécuter la requête
     * @param string $order       L'ordre (asc ou desc)
     * @param int    $limit       Le nombre d'éléments à récupérer
     * @param int    $offset      L'offset de départ
     */
    public function searchQueryBuilder(string $searchField = 'id', string $order = null, int $limit = null, int $offset = null): QueryBuilder
    {
        $qb = $this->_em
            ->createQueryBuilder()
            ->select('characters')
            ->from($this->_entityName, 'characters')
            ->leftJoin('characters.job', 'job')->addSelect('job')
            ->leftJoin('characters.people', 'people')->addSelect('people')
            ->leftJoin('characters.birthPlace', 'birthplace')->addSelect('birthplace')
        ;

        if (null !== $searchField && null !== $order) {
            if ('job' === $searchField) {
                $qb
                    ->addOrderBy('job.name', $order)
                ;
            } elseif ('people' === $searchField) {
                $qb->orderBy('people.name');
            } elseif ('birthplace' === $searchField) {
                $qb->orderBy('birthplace.name');
            } else {
                $qb->orderBy('characters.'.$searchField, $order);
            }
        }

        if (null !== $offset) {
            $qb->setFirstResult($offset);
        }

        if (null !== $limit) {
            $qb->setMaxResults($limit);
        }

        return $qb;
    }

    /**
     * @param string $searchField
     * @param string $order
     * @param int    $limit
     * @param int    $offset
     *
     * @return Character[]
     */
    public function findSearch($searchField = 'id', $order = 'asc', $limit = 20, $offset = 0)
    {
        return $this
            ->searchQueryBuilder($searchField, $order, $limit, $offset)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param string $searchField
     * @param string $order
     *
     * @return int
     */
    public function countSearch($searchField = 'id', $order = 'asc')
    {
        return (int) $this
            ->searchQueryBuilder($searchField, $order)
            ->select('count (characters.id) as number')
            ->getQuery()
            ->getScalarResult()[0]['number']
        ;
    }
}
