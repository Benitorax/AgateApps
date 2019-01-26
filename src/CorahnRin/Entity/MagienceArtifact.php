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
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="artifacts")
 * @ORM\Entity
 */
class MagienceArtifact
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
     * @ORM\Column(type="string", length=70, nullable=false, unique=true)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $price;

    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    protected $consumption;

    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    protected $consumptionInterval;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $tank;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $resistance;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $vulnerability;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $handling;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $damage;

    /**
     * @var Flux
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Flux")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $flux;

    /**
     * @var \Datetime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $created;

    /**
     * @var \Datetime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $updated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    protected $deleted;

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
     * @return MagienceArtifact
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
     * Set price.
     *
     * @param int $price
     *
     * @return MagienceArtifact
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
     * Set consumption.
     *
     * @param int $consumption
     *
     * @return MagienceArtifact
     *
     * @codeCoverageIgnore
     */
    public function setConsumption($consumption)
    {
        $this->consumption = $consumption;

        return $this;
    }

    /**
     * Get consumption.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getConsumption()
    {
        return $this->consumption;
    }

    /**
     * Set consumptionInterval.
     *
     * @param int $consumptionInterval
     *
     * @return MagienceArtifact
     *
     * @codeCoverageIgnore
     */
    public function setConsumptionInterval($consumptionInterval)
    {
        $this->consumptionInterval = $consumptionInterval;

        return $this;
    }

    /**
     * Get consumptionInterval.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getConsumptionInterval()
    {
        return $this->consumptionInterval;
    }

    /**
     * Set tank.
     *
     * @param int $tank
     *
     * @return MagienceArtifact
     *
     * @codeCoverageIgnore
     */
    public function setTank($tank)
    {
        $this->tank = $tank;

        return $this;
    }

    /**
     * Get tank.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getTank()
    {
        return $this->tank;
    }

    /**
     * Set resistance.
     *
     * @param int $resistance
     *
     * @return MagienceArtifact
     *
     * @codeCoverageIgnore
     */
    public function setResistance($resistance)
    {
        $this->resistance = $resistance;

        return $this;
    }

    /**
     * Get resistance.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getResistance()
    {
        return $this->resistance;
    }

    /**
     * Set vulnerability.
     *
     * @param string $vulnerability
     *
     * @return MagienceArtifact
     *
     * @codeCoverageIgnore
     */
    public function setVulnerability($vulnerability)
    {
        $this->vulnerability = $vulnerability;

        return $this;
    }

    /**
     * Get vulnerability.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getVulnerability()
    {
        return $this->vulnerability;
    }

    /**
     * Set handling.
     *
     * @param string $handling
     *
     * @return MagienceArtifact
     *
     * @codeCoverageIgnore
     */
    public function setHandling($handling)
    {
        $this->handling = $handling;

        return $this;
    }

    /**
     * Get handling.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getHandling()
    {
        return $this->handling;
    }

    /**
     * Set damage.
     *
     * @param int $damage
     *
     * @return MagienceArtifact
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
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return MagienceArtifact
     *
     * @codeCoverageIgnore
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime
     *
     * @codeCoverageIgnore
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated.
     *
     * @param \DateTime $updated
     *
     * @return MagienceArtifact
     *
     * @codeCoverageIgnore
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated.
     *
     * @return \DateTime
     *
     * @codeCoverageIgnore
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set flux.
     *
     * @param Flux $flux
     *
     * @return MagienceArtifact
     *
     * @codeCoverageIgnore
     */
    public function setFlux(Flux $flux = null)
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
     * Set description.
     *
     * @param string $description
     *
     * @return MagienceArtifact
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
     * Set deleted.
     *
     * @param \DateTime $deleted
     *
     * @return MagienceArtifact
     *
     * @codeCoverageIgnore
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted.
     *
     * @return \DateTime
     *
     * @codeCoverageIgnore
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
}
