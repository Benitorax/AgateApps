<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\CorahnRinBundle\Entity\CharacterProperties;

use CorahnRin\CorahnRinBundle\Entity\Avantages;
use CorahnRin\CorahnRinBundle\Entity\Characters;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CharAdvantages.
 *
 * @ORM\Table(name="characters_avantages")
 * @ORM\Entity()
 */
class CharAdvantages
{
    /**
     * @var Characters
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="CorahnRin\CorahnRinBundle\Entity\Characters", inversedBy="advantages")
     * @Assert\NotNull()
     */
    protected $character;

    /**
     * @var Avantages
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="CorahnRin\CorahnRinBundle\Entity\Avantages")
     * @Assert\NotNull()
     */
    protected $advantage;

    /**
     * @var bool
     *
     * @ORM\Column(type="integer")
     * @Assert\NotNull()
     * @Assert\GreaterThanOrEqual(value=0)
     */
    protected $doubleValue;

    /**
     * Set doubleValue.
     *
     * @param int $doubleValue
     *
     * @return CharAdvantages
     *
     * @codeCoverageIgnore
     */
    public function setDoubleValue($doubleValue)
    {
        $this->doubleValue = $doubleValue;

        return $this;
    }

    /**
     * Get doubleValue.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getDoubleValue()
    {
        return $this->doubleValue;
    }

    /**
     * Set character.
     *
     * @param Characters $character
     *
     * @return CharAdvantages
     *
     * @codeCoverageIgnore
     */
    public function setCharacter(Characters $character)
    {
        $this->character = $character;

        return $this;
    }

    /**
     * Get character.
     *
     * @return Characters
     *
     * @codeCoverageIgnore
     */
    public function getCharacter()
    {
        return $this->character;
    }

    /**
     * Set avantage.
     *
     * @param Avantages $advantage
     *
     * @return CharAdvantages
     *
     * @codeCoverageIgnore
     */
    public function setAdvantage(Avantages $advantage)
    {
        $this->advantage = $advantage;

        return $this;
    }

    /**
     * Get avantage.
     *
     * @return Avantages
     *
     * @codeCoverageIgnore
     */
    public function getAdvantage()
    {
        return $this->advantage;
    }
}
