<?php

namespace EsterenMaps\MapsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EsterenMaps\MapsBundle\Cache\ClearerEntityInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as Serializer;

/**
 * MarkersType.
 *
 * @ORM\Table(name="maps_markers_types")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @ORM\Entity()
 * @Serializer\ExclusionPolicy("all")
 */
class MarkersTypes implements ClearerEntityInterface
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Serializer\Expose
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false, unique=true)
     * @Serializer\Expose
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Serializer\Expose
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255, nullable=false)
     */
    protected $icon = '';

    /**
     * @var int
     * @ORM\Column(name="icon_width", type="integer")
     * @Serializer\Expose
     */
    protected $iconWidth = 0;

    /**
     * @var int
     * @ORM\Column(name="icon_height", type="integer")
     * @Serializer\Expose
     */
    protected $iconHeight = 0;

    /**
     * @var int
     * @ORM\Column(name="icon_center_x", type="integer", nullable=true)
     * @Serializer\Expose
     */
    protected $iconCenterX;

    /**
     * @var int
     * @ORM\Column(name="icon_center_y", type="integer", nullable=true)
     * @Serializer\Expose
     */
    protected $iconCenterY;

    /**
     * @var Markers[]
     *
     * @ORM\OneToMany(targetEntity="Markers", mappedBy="markerType")
     */
    protected $markers;

    public function __toString()
    {
        return $this->id.' - '.$this->name;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->markers = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
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
     * @return MarkersTypes
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
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add markers.
     *
     * @param Markers $markers
     *
     * @return MarkersTypes
     */
    public function addMarker(Markers $markers)
    {
        $this->markers[] = $markers;

        return $this;
    }

    /**
     * Remove markers.
     *
     * @param Markers $markers
     */
    public function removeMarker(Markers $markers)
    {
        $this->markers->removeElement($markers);
    }

    /**
     * Get markers.
     *
     * @return Markers[]
     */
    public function getMarkers()
    {
        return $this->markers;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     *
     * @return $this
     */
    public function setIcon($icon = null)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return string
     * @Serializer\VirtualProperty()
     */
    public function getWebIcon()
    {
        return '/img/markerstypes/'.$this->icon;
    }

    /**
     * @return int
     */
    public function getIconWidth()
    {
        return $this->iconWidth;
    }

    /**
     * @param int $iconWidth
     *
     * @return MarkersTypes
     */
    public function setIconWidth($iconWidth)
    {
        $this->iconWidth = $iconWidth;

        return $this;
    }

    /**
     * @return int
     */
    public function getIconHeight()
    {
        return $this->iconHeight;
    }

    /**
     * @param int $iconHeight
     *
     * @return MarkersTypes
     */
    public function setIconHeight($iconHeight)
    {
        $this->iconHeight = $iconHeight;

        return $this;
    }

    /**
     * @return int
     */
    public function getIconCenterX()
    {
        return $this->iconCenterX;
    }

    /**
     * @param int $iconCenterX
     *
     * @return MarkersTypes
     */
    public function setIconCenterX($iconCenterX)
    {
        $this->iconCenterX = $iconCenterX;

        return $this;
    }

    /**
     * @return int
     */
    public function getIconCenterY()
    {
        return $this->iconCenterY;
    }

    /**
     * @param int $iconCenterY
     *
     * @return MarkersTypes
     */
    public function setIconCenterY($iconCenterY)
    {
        $this->iconCenterY = $iconCenterY;

        return $this;
    }
}
