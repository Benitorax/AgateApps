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

use CorahnRin\Entity\Advantage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Orbitale\Component\DoctrineTools\EntityRepositoryHelperTrait;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CharacterAdvantageRepository extends ServiceEntityRepository
{
    use EntityRepositoryHelperTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Advantage::class);
    }

    /**
     * @return Advantage[][]
     */
    public function findAllDifferenciated()
    {
        /** @var Advantage[] $list */
        $list = $this->findAll();
        $advantages = $disadvantages = [];

        foreach ($list as $element) {
            $id = $element->getId();
            if ($element->isDisadvantage()) {
                $disadvantages[$id] = $element;
            } else {
                $advantages[$id] = $element;
            }
        }

        return [
            'advantages' => $advantages,
            'disadvantages' => $disadvantages,
        ];
    }
}
