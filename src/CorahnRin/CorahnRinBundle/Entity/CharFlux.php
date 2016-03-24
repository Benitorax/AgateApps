<?php

namespace CorahnRin\CorahnRinBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CharFlux.
 *
 * @ORM\Table(name="characters_flux")
 * @ORM\Entity()
 */
class CharFlux
{
    /**
     * @var Characters
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Characters", inversedBy="flux")
     * @Assert\NotNull()
     */
    protected $character;

    /**
     * @var Flux
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Flux")
     * @Assert\NotNull()
     */
    protected $flux;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     * @Assert\NotNull()
     * @Assert\GreaterThanOrEqual(value=0)
     */
    protected $quantity;

    /**
     * Set flux.
     *
     * @param int $flux
     *
     * @return CharFlux
     */
    public function setFlux($flux)
    {
        $this->flux = $flux;

        return $this;
    }

    /**
     * Get flux.
     *
     * @return int
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
     */
    public function getCharacter()
    {
        return $this->character;
    }
}
