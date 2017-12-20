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

use CorahnRin\Entity\Characters;
use CorahnRin\Entity\Setbacks;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CharSetbacks.
 *
 * @ORM\Table(name="characters_setbacks")
 * @ORM\Entity()
 */
class CharSetbacks
{
    /**
     * @var Characters
     *
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Characters", inversedBy="setbacks")
     * @Assert\NotNull()
     */
    protected $character;

    /**
     * @var Setbacks
     *
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Setbacks")
     * @Assert\NotNull()
     */
    protected $setback;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $isAvoided = false;

    /**
     * @param Characters $character
     *
     * @return CharSetbacks
     *
     * @codeCoverageIgnore
     */
    public function setCharacter(Characters $character)
    {
        $this->character = $character;

        return $this;
    }

    /**
     * @return Characters
     *
     * @codeCoverageIgnore
     */
    public function getCharacter()
    {
        return $this->character;
    }

    /**
     * @param Setbacks $setback
     *
     * @return CharSetbacks
     *
     * @codeCoverageIgnore
     */
    public function setSetback(Setbacks $setback)
    {
        $this->setback = $setback;

        return $this;
    }

    /**
     * @return Setbacks
     *
     * @codeCoverageIgnore
     */
    public function getSetback()
    {
        return $this->setback;
    }

    /**
     * @param bool $isAvoided
     *
     * @return CharSetbacks
     *
     * @codeCoverageIgnore
     */
    public function setAvoided($isAvoided)
    {
        $this->isAvoided = $isAvoided;

        return $this;
    }

    /**
     * @return bool
     *
     * @codeCoverageIgnore
     */
    public function isAvoided()
    {
        return $this->isAvoided;
    }
}
