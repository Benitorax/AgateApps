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

use Doctrine\ORM\Mapping as ORM;
use EsterenMaps\Cache\EntityToClearInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Routes.
 *
 * @ORM\Table(name="maps_routes")
 * @ORM\Entity(repositoryClass="EsterenMaps\Repository\RoutesRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Routes implements EntityToClearInterface, \JsonSerializable
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
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
     * @ORM\Column(name="coordinates", type="text")
     *
     * @Assert\Type("string")
     */
    protected $coordinates = '';

    /**
     * @var int
     *
     * @ORM\Column(name="distance", type="float", precision=12, scale=6, options={"default" = 0})
     *
     * @Assert\GreaterThanOrEqual(0)
     */
    protected $distance = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="forced_distance", type="float", precision=12, scale=6, nullable=true)
     *
     * @Assert\Type("int")
     * @Assert\GreaterThanOrEqual(0)
     */
    protected $forcedDistance;

    /**
     * @var bool
     *
     * @ORM\Column(name="guarded", type="boolean")
     *
     * @Assert\Type("bool")
     */
    protected $guarded = false;

    /**
     * @var Markers
     *
     * @ORM\ManyToOne(targetEntity="Markers", inversedBy="routesStart")
     * @ORM\JoinColumn(name="marker_start_id", nullable=true)
     *
     * @Assert\Type("EsterenMaps\Entity\Markers")
     * @Assert\NotBlank
     */
    protected $markerStart;

    /**
     * @var Markers
     *
     * @ORM\ManyToOne(targetEntity="Markers", inversedBy="routesEnd")
     * @ORM\JoinColumn(name="marker_end_id", nullable=true)
     *
     * @Assert\Type("EsterenMaps\Entity\Markers")
     * @Assert\NotBlank
     */
    protected $markerEnd;

    /**
     * @var Maps
     *
     * @ORM\ManyToOne(targetEntity="Maps", inversedBy="routes")
     * @ORM\JoinColumn(name="map_id", nullable=false)
     *
     * @Assert\Type("EsterenMaps\Entity\Maps")
     * @Assert\NotBlank
     */
    protected $map;

    /**
     * @var Faction
     *
     * @ORM\ManyToOne(targetEntity="Faction", inversedBy="routes")
     * @ORM\JoinColumn(name="faction_id", nullable=true)
     *
     * @Assert\Type("EsterenMaps\Entity\Faction")
     */
    protected $faction;

    /**
     * @var RoutesTypes
     *
     * @ORM\ManyToOne(targetEntity="RoutesTypes", inversedBy="routes")
     * @ORM\JoinColumn(name="route_type_id", nullable=false)
     *
     * @Assert\Type("EsterenMaps\Entity\RoutesTypes")
     * @Assert\NotBlank
     */
    protected $routeType;

    /**
     * If it's false, the object won't be refreshed by the listener.
     *
     * @var bool
     */
    public $refresh = true;

    public function __toString()
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'coordinates' => $this->coordinates,
            'distance' => $this->distance,
            'forcedDistance' => $this->forcedDistance,
            'guarded' => $this->guarded,
            'markerStart' => $this->markerStart ? $this->markerStart->getId() : null,
            'markerEnd' => $this->markerEnd ? $this->markerEnd->getId() : null,
            'map' => $this->map ? $this->map->getId() : null,
            'routeType' => $this->routeType ? $this->routeType->getId() : null,
            'faction' => $this->faction ? $this->faction->getId() : null,
        ];
    }

    /**
     * {@inheritdoc}
     */
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

        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->coordinates = $data['coordinates'];
        $this->forcedDistance = $data['forcedDistance'];
        $this->guarded = $data['guarded'];
        $this->map = $data['map'];
        $this->faction = $data['faction'];
        $this->routeType = $data['routeType'];
        $this->markerStart = $data['markerStart'];
        $this->markerEnd = $data['markerEnd'];

        if ($this->map && $this->coordinates) {
            $this->calcDistance();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return (string) $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function setCoordinates(string $coordinates): self
    {
        try {
            \json_decode($coordinates, true);
        } catch (\Throwable $e) {
        }

        if (JSON_ERROR_NONE !== $code = \json_last_error()) {
            throw new \InvalidArgumentException($code.':'.\json_last_error_msg());
        }

        $this->coordinates = $coordinates;

        $this->calcDistance();

        return $this;
    }

    public function getCoordinates(): string
    {
        return (string) $this->coordinates;
    }

    public function setMap(?Maps $map): self
    {
        $this->map = $map;

        return $this;
    }

    public function getMap(): ?Maps
    {
        return $this->map;
    }

    public function setFaction(?Faction $faction): self
    {
        $this->faction = $faction;

        return $this;
    }

    public function getFaction(): ?Faction
    {
        return $this->faction;
    }

    public function setRouteType(?RoutesTypes $routeType): self
    {
        $this->routeType = $routeType;

        return $this;
    }

    public function getRouteType(): ?RoutesTypes
    {
        return $this->routeType;
    }

    public function setMarkerStart(?Markers $markerStart): self
    {
        $this->markerStart = $markerStart;

        return $this;
    }

    public function getMarkerStart(): ?Markers
    {
        return $this->markerStart;
    }

    public function setMarkerEnd(?Markers $markerEnd): self
    {
        $this->markerEnd = $markerEnd;

        return $this;
    }

    public function getMarkerEnd(): ?Markers
    {
        return $this->markerEnd;
    }

    public function getDistance(): int
    {
        return (int) $this->distance;
    }

    public function setDistance(int $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function setForcedDistance(?int $forcedDistance): self
    {
        $this->forcedDistance = $forcedDistance;

        return $this;
    }

    public function getForcedDistance(): ?int
    {
        return $this->forcedDistance;
    }

    public function setGuarded(?bool $guarded): self
    {
        $this->guarded = $guarded;

        return $this;
    }

    public function isGuarded(): bool
    {
        return (bool) $this->guarded;
    }

    public function isLocalized(): bool
    {
        return null !== $this->coordinates && \count($this->getDecodedCoordinates());
    }

    public function getDecodedCoordinates(): array
    {
        return (array) \json_decode($this->coordinates, true);
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function refresh(): void
    {
        if (!$this->refresh) {
            return;
        }

        if (!$this->coordinates) {
            $this->coordinates = '[]';
        }
        $coords = \json_decode($this->coordinates, true);

        if ($this->markerStart && isset($coords[0])) {
            $coords[0] = [
                'lat' => $this->markerStart->getLatitude(),
                'lng' => $this->markerStart->getLongitude(),
            ];
        }
        if ($this->markerEnd) {
            $coords[\count($coords) - ((int) (\count($coords) > 1))] = [
                'lat' => $this->markerEnd->getLatitude(),
                'lng' => $this->markerEnd->getLongitude(),
            ];
        }

        $this->calcDistance();

        $this->setCoordinates(\json_encode($coords));
    }

    public function calcDistance(): int
    {
        if (!$this->map) {
            return 0;
        }

        // Override distance if we have set forcedDistance
        if ($this->forcedDistance) {
            $this->distance = $this->forcedDistance;

            return $this->forcedDistance;
        }

        // Else, we force the null value
        $this->forcedDistance = null;

        $distance = 0;
        $points = \json_decode($this->coordinates, true);

        \reset($points);

        // Use classic Pythagore's theorem to calculate distances.
        while ($current = \current($points)) {
            $next = \next($points);
            if (false !== $next) {
                $currentX = $current['lng'];
                $currentY = $current['lat'];
                $nextX = $next['lng'];
                $nextY = $next['lat'];

                $distance += \sqrt(
                    ($nextX * $nextX)
                    - (2 * $currentX * $nextX)
                    + ($currentX * $currentX)
                    + ($nextY * $nextY)
                    - (2 * $currentY * $nextY)
                    + ($currentY * $currentY)
                );
            }
        }

        // Apply map ratio to distance.
        $distance = $this->map->getCoordinatesRatio() * $distance;

        /**
         * The "substr" trick truncates the numbers, else mysql 5.7 would throw a warning.
         * This parameter should depend on the "precision" specified in the $distance property.
         *
         * @see Routes::$distance
         */
        $floatPrecision = 12;

        $distance = (float) \mb_substr($distance, 0, $floatPrecision);

        if ($distance !== (float) \mb_substr($this->distance, 0, $floatPrecision)) {
            $this->distance = $distance;
        }

        return $distance;
    }
}
