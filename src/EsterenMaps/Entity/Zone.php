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

use Doctrine\ORM\Mapping as ORM;
use EsterenMaps\Cache\EntityToClearInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="maps_zones")
 * @ORM\Entity(repositoryClass="EsterenMaps\Repository\ZonesRepository")
 */
class Zone implements EntityToClearInterface, \JsonSerializable
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
     * @ORM\Column(name="coordinates", type="text")
     *
     * @Assert\Type("string")
     */
    protected $coordinates = '';

    /**
     * @var Map
     *
     * @ORM\ManyToOne(targetEntity="EsterenMaps\Entity\Map", inversedBy="zones")
     * @ORM\JoinColumn(name="map_id", nullable=false)
     *
     * @Assert\Type("EsterenMaps\Entity\Map")
     * @Assert\NotBlank
     */
    protected $map;

    /**
     * @var Faction
     *
     * @ORM\ManyToOne(targetEntity="EsterenMaps\Entity\Faction", inversedBy="zones")
     * @ORM\JoinColumn(name="faction_id", nullable=true)
     *
     * @Assert\Type("EsterenMaps\Entity\Faction")
     */
    protected $faction;

    /**
     * @var ZoneType
     *
     * @ORM\ManyToOne(targetEntity="EsterenMaps\Entity\ZoneType", inversedBy="zones")
     * @ORM\JoinColumn(name="zone_type_id", nullable=false)
     *
     * @Assert\Type("EsterenMaps\Entity\ZoneType")
     * @Assert\NotBlank
     */
    protected $zoneType;

    public function __toString(): string
    {
        return (string) $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'coordinates' => $this->coordinates,
            'map' => $this->map ? $this->map->getId() : null,
            'zoneType' => $this->zoneType ? $this->zoneType->getId() : null,
            'faction' => $this->faction ? $this->faction->getId() : null,
        ];
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

    private function hydrateIncomingData(array $data): void
    {
        $data = \array_merge($this->toArray(), $data);

        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->coordinates = $data['coordinates'];
        $this->map = $data['map'];
        $this->faction = $data['faction'];
        $this->zoneType = $data['zoneType'];

        $this->flattenCoordinates();
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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return (string) $this->name;
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

        return $this;
    }

    public function getCoordinates(): string
    {
        return $this->coordinates;
    }

    public function setMap(?Map $map): self
    {
        $this->map = $map;

        return $this;
    }

    public function getMap(): ?Map
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

    public function setZoneType(?ZoneType $zoneType): self
    {
        $this->zoneType = $zoneType;

        return $this;
    }

    public function getZoneType(): ?ZoneType
    {
        return $this->zoneType;
    }

    public function isLocalized(): bool
    {
        return null !== $this->coordinates && \count($this->getDecodedCoordinates());
    }

    public function getDecodedCoordinates(): array
    {
        return \json_decode($this->coordinates, true) ?: [];
    }

    private function flattenCoordinates(): void
    {
        $coordinates = $this->getDecodedCoordinates();

        if (isset($coordinates[0][0]['lat'])) {
            // Array of array of latlngs...
            $flattened = [];
            foreach ($coordinates as $coordinateList) {
                foreach ($coordinateList as $coordinate) {
                    $coordinate['lat'] = (float) $coordinate['lat'];
                    $coordinate['lng'] = (float) $coordinate['lng'];
                    $flattened[] = $coordinate;
                }
            }
            $coordinates = $flattened;
        }

        $this->setCoordinates(\json_encode($coordinates));
    }
}
