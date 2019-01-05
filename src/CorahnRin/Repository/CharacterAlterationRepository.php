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

use CorahnRin\Entity\CharacterAlteration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Orbitale\Component\DoctrineTools\EntityRepositoryHelperTrait;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CharacterAlterationRepository extends ServiceEntityRepository
{
    use EntityRepositoryHelperTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CharacterAlteration::class);
    }

    /**
     * @return CharacterAlteration[][]
     */
    public function findAllDifferenciated()
    {
        /** @var CharacterAlteration[] $list */
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
