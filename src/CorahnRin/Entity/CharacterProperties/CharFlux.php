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
use CorahnRin\Entity\Flux;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CharFlux.
 *
 * @ORM\Table(name="characters_flux")
 * @ORM\Entity
 */
class CharFlux
{
    /**
     * @var Characters
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Characters", inversedBy="flux")
     * @Assert\NotNull
     */
    protected $character;

    /**
     * @var Flux
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Flux")
     * @Assert\NotNull
     */
    protected $flux;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     * @Assert\NotNull
     * @Assert\GreaterThanOrEqual(value=0)
     */
    protected $quantity;

    /**
     * Set flux.
     *
     * @param int $flux
     *
     * @return CharFlux
     *
     * @codeCoverageIgnore
     */
    public function setFlux($flux)
    {
        $this->flux = $flux;

        return $this;
    }

    /**
     * Get flux.
     *
     * @return Flux
     *
     * @codeCoverageIgnore
     */
    public function getFlux()
    {
        return $this->flux;
    }

    /**
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return CharFlux
     *
     * @codeCoverageIgnore
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set character.
     *
     * @param Characters $character
     *
     * @return CharFlux
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
}
