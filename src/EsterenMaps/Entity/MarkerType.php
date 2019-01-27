<?php

declare(strict_types=1);

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
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use EsterenMaps\Cache\EntityToClearInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * MarkersType.
 *
 * @ORM\Table(name="maps_markers_types")
 * @ORM\Entity(repositoryClass="EsterenMaps\Repository\MarkersTypesRepository")
 */
class MarkerType implements EntityToClearInterface
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
     * @ORM\Column(name="icon", type="string", length=255, nullable=false)
     */
    protected $icon;

    /**
     * @var int
     * @ORM\Column(name="icon_width", type="integer")
     */
    protected $iconWidth = 0;

    /**
     * @var int
     * @ORM\Column(name="icon_height", type="integer")
     */
    protected $iconHeight = 0;

    /**
     * @var int
     * @ORM\Column(name="icon_center_x", type="integer", nullable=true)
     */
    protected $iconCenterX;

    /**
     * @var int
     * @ORM\Column(name="icon_center_y", type="integer", nullable=true)
     */
    protected $iconCenterY;

    /**
     * @var Marker[]|Collection
     *
     * @ORM\OneToMany(targetEntity="EsterenMaps\Entity\Marker", mappedBy="markerType")
     */
    protected $markers;

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->markers = new ArrayCollection();
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = (int) $id;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return (string) $this->name;
    }

    public function addMarker(Marker $markers): self
    {
        $this->markers[] = $markers;

        return $this;
    }

    public function removeMarker(Marker $markers): self
    {
        $this->markers->removeElement($markers);

        return $this;
    }

    /**
     * @return Marker[]
     */
    public function getMarkers(): iterable
    {
        return $this->markers;
    }

    public function getDescription(): string
    {
        return (string) $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getWebIcon(): string
    {
        return '/img/markerstypes/'.$this->icon;
    }

    public function getIconWidth(): int
    {
        return (int) $this->iconWidth;
    }

    public function setIconWidth(int $iconWidth): self
    {
        $this->iconWidth = $iconWidth;

        return $this;
    }

    public function getIconHeight(): int
    {
        return (int) $this->iconHeight;
    }

    public function setIconHeight(int $iconHeight): self
    {
        $this->iconHeight = $iconHeight;

        return $this;
    }

    public function getIconCenterX(): ?int
    {
        return $this->iconCenterX;
    }

    public function setIconCenterX(?int $iconCenterX): self
    {
        $this->iconCenterX = $iconCenterX;

        return $this;
    }

    public function getIconCenterY(): ?int
    {
        return $this->iconCenterY;
    }

    public function setIconCenterY(?int $iconCenterY): self
    {
        $this->iconCenterY = $iconCenterY;

        return $this;
    }
}
