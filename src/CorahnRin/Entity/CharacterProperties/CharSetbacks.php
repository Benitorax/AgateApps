<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Entity\CharacterProperties;

use CorahnRin\Entity\Character;
use CorahnRin\Entity\Setbacks;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CharSetbacks.
 *
 * @ORM\Table(name="characters_setbacks")
 * @ORM\Entity
 */
class CharSetbacks
{
    /**
     * @var Character
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Character", inversedBy="setbacks")
     * @Assert\NotNull
     */
    protected $character;

    /**
     * @var Setbacks
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Setbacks")
     * @Assert\NotNull
     */
    protected $setback;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $isAvoided = false;

    /**
     * @return CharSetbacks
     *
     * @codeCoverageIgnore
     */
    public function setCharacter(Character $character)
    {
        $this->character = $character;

        return $this;
    }

    /**
     * @return Character
     *
     * @codeCoverageIgnore
     */
    public function getCharacter()
    {
        return $this->character;
    }

    /**
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
