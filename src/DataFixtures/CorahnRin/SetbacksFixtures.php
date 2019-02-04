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

namespace DataFixtures\CorahnRin;

use CorahnRin\Entity\CharacterProperties\Bonuses;
use CorahnRin\Entity\Setback;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Orbitale\Component\DoctrineTools\AbstractFixture;

class SetbacksFixtures extends AbstractFixture implements ORMFixtureInterface
{
    public function getOrder(): int
    {
        return 3;
    }

    protected function getEntityClass(): string
    {
        return Setback::class;
    }

    protected function getObjects()
    {
        $book = $this->getReference('corahnrin-book-2');

        return [
            [
                'name' => 'Poisse',
                'description' => 'Tirer une deuxième fois, ignorer les 1 supplémentaires',
                'malus' => '',
                'book' => $book,
                'isLucky' => false,
                'isUnlucky' => true,
            ],
            [
                'name' => 'Séquelle',
                'description' => '-1 Vigueur, et une séquelle physique (cicatrice...)',
                'malus' => Bonuses::STAMINA,
                'book' => $book,
                'isLucky' => false,
                'isUnlucky' => false,
            ],
            [
                'name' => 'Adversaire',
                'description' => 'Le personnage s\'est fait un ennemi (à la discrétion du MJ)',
                'malus' => '',
                'book' => $book,
                'isLucky' => false,
                'isUnlucky' => false,
            ],
            [
                'name' => 'Rumeur',
                'description' => 'Une information, vraie ou non, circule à propos du personnage',
                'malus' => '',
                'book' => $book,
                'isLucky' => false,
                'isUnlucky' => false,
            ],
            [
                'name' => 'Amour tragique',
                'description' => '+1 point de Trauma définitif, mauvais souvenir',
                'malus' => Bonuses::TRAUMA,
                'book' => $book,
                'isLucky' => false,
                'isUnlucky' => false,
            ],
            [
                'name' => 'Maladie',
                'description' => '-1 Vigueur, mais a survécu à une maladie normalement mortelle',
                'malus' => Bonuses::STAMINA,
                'book' => $book,
                'isLucky' => false,
                'isUnlucky' => false,
            ],
            [
                'name' => 'Violence',
                'description' => '+1 point de Trauma définitif, souvenir violent, gore, horrible...',
                'malus' => Bonuses::TRAUMA,
                'book' => $book,
                'isLucky' => false,
                'isUnlucky' => false,
            ],
            [
                'name' => 'Solitude',
                'description' => 'Les proches, amis ou famille du personnage sont morts de façon douteuse',
                'malus' => '',
                'book' => $book,
                'isLucky' => false,
                'isUnlucky' => false,
            ],
            [
                'name' => 'Pauvreté',
                'description' => 'Le personnage ne possède qu\'une mauvaise arme, ou outil, a des dettes d\'héritage, de vol... Il n\'a plus d\'argent, sa famille a été ruinée ou lui-même est ruiné d\'une façon ou d\'une autre, et aucun évènement ou avantage ne peut y remédier.',
                'malus' => Bonuses::MONEY_0,
                'book' => $book,
                'isLucky' => false,
                'isUnlucky' => false,
                'disabledAdvantages' => [
                    $this->getReference('corahnrin-avantage-4'),
                    $this->getReference('corahnrin-avantage-5'),
                    $this->getReference('corahnrin-avantage-6'),
                    $this->getReference('corahnrin-avantage-7'),
                    $this->getReference('corahnrin-avantage-8'),
                    $this->getReference('corahnrin-avantage-47'),
                ],
            ],
            [
                'name' => 'Chance',
                'description' => 'Le personnage est passé à côté de la catastrophe !',
                'malus' => '',
                'book' => $book,
                'isLucky' => true,
                'isUnlucky' => false,
            ],
        ];
    }
}
