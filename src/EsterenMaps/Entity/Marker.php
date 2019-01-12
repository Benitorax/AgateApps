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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Markers.
 *
 * @ORM\Table(name="maps_markers")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="EsterenMaps\Repository\MarkersRepository")
 */
class Marker implements EntityToClearInterface, \JsonSerializable
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false, unique=true)
     *
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     *
     * @Assert\Type("string")
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="altitude", type="string", length=255, options={"default" = 0})
     *
     * @Assert\Type("float")
     */
    protected $altitude = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=255, options={"default" = 0})
     *
     * @Assert\Type("float")
     */
    protected $latitude = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=255, options={"default" = 0})
     *
     * @Assert\Type("float")
     */
    protected $longitude = 0;

    /**
     * @var Faction
     *
     * @ORM\ManyToOne(targetEntity="EsterenMaps\Entity\Faction", inversedBy="markers")
     * @ORM\JoinColumn(name="faction_id", nullable=true)
     *
     * @Assert\Type("EsterenMaps\Entity\Faction")
     */
    protected $faction;

    /**
     * @var Map
     *
     * @ORM\ManyToOne(targetEntity="EsterenMaps\Entity\Map", inversedBy="markers")
     * @ORM\JoinColumn(name="map_id", nullable=false)
     *
     * @Assert\Type("EsterenMaps\Entity\Map")
     * @Assert\NotBlank
     */
    protected $map;

    /**
     * @var MarkerType
     *
     * @ORM\ManyToOne(targetEntity="EsterenMaps\Entity\MarkerType", inversedBy="markers")
     * @ORM\JoinColumn(name="marker_type_id", nullable=false)
     *
     * @Assert\Type("EsterenMaps\Entity\MarkerType")
     * @Assert\NotBlank
     */
    protected $markerType;

    /**
     * @var Route[]
     *
     * @ORM\OneToMany(targetEntity="EsterenMaps\Entity\Route", mappedBy="markerStart")
     */
    protected $routesStart;

    /**
     * @var Route[]
     *
     * @ORM\OneToMany(targetEntity="EsterenMaps\Entity\Route", mappedBy="markerEnd")
     */
    protected $routesEnd;

    /**
     * If true, the self::updateRoutesCoordinates() method will force the update of the associated routes.
     */
    protected $forceRoutesUpdate = false;

    public function __toString(): string
    {
        return (string) $this->name;
    }

    public function __construct()
    {
        $this->routesStart = new ArrayCollection();
        $this->routesEnd = new ArrayCollection();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'altitude' => (float) $this->altitude,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'faction' => $this->faction ? $this->faction->getId() : null,
            'map' => $this->map ? $this->map->getId() : null,
            'markerType' => $this->markerType ? $this->markerType->getId() : null,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public static function fromApi(array $data): self
    {
        $route = new static();

        $route->hydrateIncomingData($data);

        return $route;
    }

    public function updateFromApi(array $data): void
    {
        $this->hydrateIncomingData($data);
    }

    private function hydrateIncomingData(array $data)
    {
        $data = \array_merge($this->toArray(), $data);

        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->altitude = $data['altitude'];
        $this->latitude = $data['latitude'];
        $this->longitude = $data['longitude'];
        $this->faction = $data['faction'];
        $this->map = $data['map'];
        $this->markerType = $data['markerType'];

        $this->forceRoutesUpdate = true;
        $this->updateRoutesCoordinates();
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return (int) $this->id;
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

    public function setFaction(Faction $faction = null): self
    {
        $this->faction = $faction;

        return $this;
    }

    public function getFaction(): ?Faction
    {
        return $this->faction;
    }

    public function setMap(Map $map = null): self
    {
        $this->map = $map;

        return $this;
    }

    public function getMap(): ?Map
    {
        return $this->map;
    }

    public function setMarkerType(MarkerType $markerType): self
    {
        $this->markerType = $markerType;

        return $this;
    }

    public function getMarkerType(): ?MarkerType
    {
        return $this->markerType;
    }

    public function addRoutesStart(Route $routesStart): self
    {
        $this->routesStart[] = $routesStart;

        return $this;
    }

    public function removeRoutesStart(Route $routesStart): self
    {
        $this->routesStart->removeElement($routesStart);

        return $this;
    }

    public function getRoutesStart(): iterable
    {
        return $this->routesStart;
    }

    public function addRoutesEnd(Route $routesEnd): self
    {
        $this->routesEnd[] = $routesEnd;

        return $this;
    }

    public function removeRoutesEnd(Route $routesEnd): self
    {
        $this->routesEnd->removeElement($routesEnd);

        return $this;
    }

    public function setAltitude(float $altitude): self
    {
        $this->altitude = $altitude;

        return $this;
    }

    public function getAltitude(): float
    {
        return (float) $this->altitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLatitude(): float
    {
        return (float) $this->latitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLongitude(): float
    {
        return (float) $this->longitude;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return (string) $this->description;
    }

    public function isLocalized(): bool
    {
        return null !== $this->latitude && null !== $this->longitude;
    }

    public function getWebIcon(): string
    {
        return $this->markerType->getWebIcon();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateRoutesCoordinates(): void
    {
        foreach ($this->routesStart as $route) {
            $route->refresh = $this->forceRoutesUpdate;
            $route->refresh();
        }

        foreach ($this->routesEnd as $route) {
            $route->refresh = $this->forceRoutesUpdate;
            $route->refresh();
        }
    }
}
