<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
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
 * ZonesTypes.
 *
 * @ORM\Table(name="maps_zones_types")
 * @ORM\Entity(repositoryClass="EsterenMaps\Repository\ZonesTypesRepository")
 */
class ZonesTypes implements EntityToClearInterface
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
     * @var Resources[]
     * @ORM\ManyToMany(targetEntity="Resources", mappedBy="zonesTypes")
     */
    protected $resources;

    /**
     * @var ZonesTypes
     *
     * @ORM\ManyToOne(targetEntity="ZonesTypes")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $parent;

    /**
     * @var Zones[]
     * @ORM\OneToMany(targetEntity="Zones", mappedBy="zoneType")
     */
    protected $zones;

    protected $children = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->resources = new ArrayCollection();
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
     * @return ZonesTypes
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
     * Add resources.
     *
     * @param Resources $resources
     *
     * @return ZonesTypes
     */
    public function addResource(Resources $resources)
    {
        $this->resources[] = $resources;

        return $this;
    }

    /**
     * Remove resources.
     *
     * @param Resources $resources
     */
    public function removeResource(Resources $resources)
    {
        $this->resources->removeElement($resources);
    }

    /**
     * Get resources.
     *
     * @return Resources[]
     *
     * @codeCoverageIgnore
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * Add zones.
     *
     * @param Zones $zones
     *
     * @return ZonesTypes
     */
    public function addZone(Zones $zones)
    {
        $this->zones[] = $zones;

        return $this;
    }

    /**
     * Remove zones.
     *
     * @param Zones $zones
     */
    public function removeZone(Zones $zones)
    {
        $this->zones->removeElement($zones);
    }

    /**
     * Get zones.
     *
     * @return Zones[]
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
     * @param ZonesTypes $parent
     *
     * @return ZonesTypes
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
     * @return ZonesTypes
     *
     * @codeCoverageIgnore
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param ZonesTypes $child
     *
     * @return $this
     */
    public function addChild($child)
    {
        $this->children[$child->getId()] = $child;

        return $this;
    }

    /**
     * @param ZonesTypes[] $children
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
     * @return ZonesTypes[]
     *
     * @codeCoverageIgnore
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param ZonesTypes $child
     *
     * @return $this
     */
    public function removeChild($child)
    {
        if (\is_numeric($child)) {
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
     * @return ZonesTypes
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
     * @return ZonesTypes|null
     */
    public function getParentByLevel($level = 0)
    {
        /** @var ZonesTypes $actualParent */
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
