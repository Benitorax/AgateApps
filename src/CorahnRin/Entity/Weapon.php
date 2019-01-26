<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Weapons.
 *
 * @ORM\Table(name="weapons")
 * @ORM\Entity(repositoryClass="CorahnRin\Repository\WeaponsRepository")
 */
class Weapon
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false, unique=true)
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var bool
     *
     * @ORM\Column(type="smallint")
     */
    protected $damage;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $price;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=5)
     */
    protected $availability;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $melee = true;

    /**
     * @var int
     *
     * @ORM\Column(name="weapon_range", type="smallint")
     */
    protected $range;

    /**
     * Get id.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Weapon
     *
     * @codeCoverageIgnore
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Weapon
     *
     * @codeCoverageIgnore
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set damage.
     *
     * @param int $damage
     *
     * @return Weapon
     *
     * @codeCoverageIgnore
     */
    public function setDamage($damage)
    {
        $this->damage = $damage;

        return $this;
    }

    /**
     * Get damage.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getDamage()
    {
        return $this->damage;
    }

    /**
     * Set price.
     *
     * @param int $price
     *
     * @return Weapon
     *
     * @codeCoverageIgnore
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set availability.
     *
     * @param string $availability
     *
     * @return Weapon
     *
     * @codeCoverageIgnore
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * Get availability.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * Set melee.
     *
     * @param int $melee
     *
     * @return Weapon
     *
     * @codeCoverageIgnore
     */
    public function setMelee($melee)
    {
        $this->melee = $melee;

        return $this;
    }

    /**
     * Get melee.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getMelee()
    {
        return $this->melee;
    }

    /**
     * Set range.
     *
     * @param int $range
     *
     * @return Weapon
     *
     * @codeCoverageIgnore
     */
    public function setRange($range)
    {
        $this->range = $range;

        return $this;
    }

    /**
     * Get range.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getRange()
    {
        return $this->range;
    }
}
