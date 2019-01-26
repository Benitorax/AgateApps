<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EsterenMaps\Cache\EntityToClearInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Table(name="maps_zones_types")
 * @ORM\Entity(repositoryClass="EsterenMaps\Repository\ZonesTypesRepository")
 */
class ZoneType implements EntityToClearInterface
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=75, nullable=true)
     */
    protected $color;

    /**
     * @var ZoneType
     *
     * @ORM\ManyToOne(targetEntity="EsterenMaps\Entity\ZoneType")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $parent;

    /**
     * @var Zone[]
     * @ORM\OneToMany(targetEntity="EsterenMaps\Entity\Zone", mappedBy="zoneType")
     */
    protected $zones;

    protected $children = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->zones = new ArrayCollection();
    }

    public function __toString()
    {
        return ($this->parent ? '> ' : '').$this->id.' '.$this->name;
    }

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
     * @return ZoneType
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
     * Add zones.
     *
     *
     * @return ZoneType
     */
    public function addZone(Zone $zones)
    {
        $this->zones[] = $zones;

        return $this;
    }

    /**
     * Remove zones.
     */
    public function removeZone(Zone $zones)
    {
        $this->zones->removeElement($zones);
    }

    /**
     * Get zones.
     *
     * @return Zone[]
     *
     * @codeCoverageIgnore
     */
    public function getZones()
    {
        return $this->zones;
    }

    /**
     * Set parent.
     *
     * @param ZoneType $parent
     *
     * @return ZoneType
     *
     * @codeCoverageIgnore
     */
    public function setParent(self $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return ZoneType
     *
     * @codeCoverageIgnore
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param ZoneType $child
     *
     * @return $this
     */
    public function addChild($child)
    {
        $this->children[$child->getId()] = $child;

        return $this;
    }

    /**
     * @param ZoneType[] $children
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @return ZoneType[]
     *
     * @codeCoverageIgnore
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param ZoneType $child
     *
     * @return $this
     */
    public function removeChild($child)
    {
        if (!\is_object($child) && isset($this->children[$child])) {
            unset($this->children[$child]);
        } elseif (\is_object($child)) {
            unset($this->children[$child->getId()]);
        }

        return $this;
    }

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     *
     * @return ZoneType
     *
     * @codeCoverageIgnore
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @param string $description
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Retourne le parent à un certain niveau d'héritage.
     *
     * @param int $level
     *
     * @return ZoneType|null
     */
    public function getParentByLevel($level = 0)
    {
        /** @var ZoneType $actualParent */
        $actualParent = $this->parent;
        if ($actualParent) {
            while ($level > 0) {
                $actualParent = $actualParent->getParent();
                --$level;
                if (!$actualParent && $level > 0) {
                    $level = 0;
                }
            }
        }

        return $actualParent;
    }
}
