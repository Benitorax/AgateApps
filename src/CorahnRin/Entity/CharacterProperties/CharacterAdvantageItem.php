<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Entity\CharacterProperties;

use CorahnRin\Entity\Advantage;
use CorahnRin\Entity\Characters;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="characters_avantages")
 * @ORM\Entity
 */
class CharacterAdvantageItem
{
    /**
     * @var Characters
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Characters", inversedBy="advantages")
     */
    protected $character;

    /**
     * @var Advantage
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Advantage")
     */
    protected $advantage;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer")
     */
    protected $score;

    /**
     * @var string
     *
     * @ORM\Column(name="indication", type="string", length=255)
     */
    protected $indication;

    public static function create(
        Characters $character,
        Advantage $advantage,
        int $score,
        string $indication
    ): self {
        $object = new self();

        $object->character = $character;
        $object->advantage = $advantage;
        $object->score = $score;
        $object->indication = $indication;

        return $object;
    }

    public function getCharacter(): Characters
    {
        return $this->character;
    }

    public function getAdvantage(): Advantage
    {
        return $this->advantage;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getIndication(): string
    {
        return $this->indication;
    }
}
