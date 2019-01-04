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
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="maps")
 * @ORM\Entity(repositoryClass="EsterenMaps\Repository\MapsRepository")
 * @Gedmo\Uploadable(allowOverwrite=true, filenameGenerator="SHA1")
 */
class Map implements EntityToClearInterface
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
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    protected $nameSlug;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     * @Gedmo\UploadableFilePath
     */
    protected $image;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var int
     *
     * @ORM\Column(name="max_zoom", type="smallint", options={"default" = 1})
     * @Assert\Range(
     *     min=1,
     *     max=50
     * )
     */
    protected $maxZoom = 10;

    /**
     * @var int
     *
     * @ORM\Column(name="start_zoom", type="smallint", options={"default" = 1})
     * @Assert\Range(
     *     min=1,
     *     max=10
     * )
     */
    protected $startZoom = 10;

    /**
     * @var int
     *
     * @ORM\Column(name="start_x", type="smallint", options={"default" = 1})
     */
    protected $startX = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="start_y", type="smallint", options={"default" = 1})
     */
    protected $startY = 0;

    /**
     * @var string
     * @ORM\Column(name="bounds", type="string", options={"default" = "[]"})
     */
    protected $bounds = '[]';

    /**
     * @var int
     *
     * @ORM\Column(name="coordinates_ratio", type="smallint", options={"default" = 1})
     */
    protected $coordinatesRatio = 1;

    /**
     * @var Routes[]|ArrayCollection
     *
     * @MaxDepth(1)
     * @ORM\OneToMany(targetEntity="Routes", mappedBy="map")
     */
    protected $routes;

    /**
     * @var Markers[]|ArrayCollection
     *
     * @MaxDepth(1)
     * @ORM\OneToMany(targetEntity="Markers", mappedBy="map")
     */
    protected $markers;

    /**
     * @var Zones[]|ArrayCollection
     *
     * @MaxDepth(1)
     * @ORM\OneToMany(targetEntity="Zones", mappedBy="map")
     */
    protected $zones;

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->routes = new ArrayCollection();
        $this->markers = new ArrayCollection();
        $this->zones = new ArrayCollection();
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
     * @param $id
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
     * @return Map
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
     * Set image.
     *
     * @param string $image
     *
     * @return Map
     *
     * @codeCoverageIgnore
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Map
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
     * Set maxZoom.
     *
     * @param bool $maxZoom
     *
     * @return Map
     *
     * @codeCoverageIgnore
     */
    public function setMaxZoom($maxZoom)
    {
        $this->maxZoom = $maxZoom;

        return $this;
    }

    /**
     * Get maxZoom.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getMaxZoom()
    {
        return $this->maxZoom;
    }

    /**
     * Add routes.
     *
     *
     * @return Map
     */
    public function addRoute(Routes $routes)
    {
        $this->routes[] = $routes;

        return $this;
    }

    /**
     * Remove routes.
     */
    public function removeRoute(Routes $routes)
    {
        $this->routes->removeElement($routes);
    }

    /**
     * Get routes.
     *
     * @return Routes[]|ArrayCollection
     *
     * @codeCoverageIgnore
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Get zone.
     *
     *
     * @return Routes|null
     */
    public function getRoute(Routes $route)
    {
        foreach ($this->routes as $mapRoute) {
            if ($mapRoute->getId() === $route->getId() ||
                $mapRoute->getName() === $route->getName()
            ) {
                return $mapRoute;
            }
        }
    }

    /**
     * Get zone.
     *
     *
     * @return Map
     */
    public function setRoute(Routes $route)
    {
        $exists = $this->getRoute($route);
        if (!$exists) {
            $this->addRoute($route);
        } else {
            $this->routes->removeElement($exists);
            $this->addRoute($route);
        }

        return $this;
    }

    /**
     * Add markers.
     *
     *
     * @return Map
     */
    public function addMarker(Markers $markers)
    {
        $this->markers[] = $markers;

        return $this;
    }

    /**
     * Remove markers.
     */
    public function removeMarker(Markers $markers)
    {
        $this->markers->removeElement($markers);
    }

    /**
     * Get markers.
     *
     * @return Markers[]|ArrayCollection
     *
     * @codeCoverageIgnore
     */
    public function getMarkers()
    {
        return $this->markers;
    }

    /**
     * Get zone.
     *
     *
     * @return Zones|null
     */
    public function getMarker(Markers $marker)
    {
        foreach ($this->markers as $mapMarker) {
            if ($mapMarker->getId() === $marker->getId() ||
                $mapMarker->getName() === $marker->getName()
            ) {
                return $mapMarker;
            }
        }
    }

    /**
     * Contrairement au nom de cette méthode, celle-ci AJOUTE un marqueur,
     *    et uniquement si celui-ci n'est pas déjà ajouté à la map.
     *
     *
     * @return $this
     */
    public function setMarker(Markers $marker)
    {
        $exists = $this->getMarker($marker);
        if (!$exists) {
            $this->addMarker($marker);
        } else {
            $this->markers->removeElement($exists);
            $this->addMarker($marker);
        }

        return $this;
    }

    /**
     * Add zones.
     *
     *
     * @return Map
     */
    public function addZone(Zones $zones)
    {
        $this->zones[] = $zones;

        return $this;
    }

    /**
     * Remove zones.
     */
    public function removeZone(Zones $zones)
    {
        $this->zones->removeElement($zones);
    }

    /**
     * Get zone.
     *
     *
     * @return Zones|null
     */
    public function getZone(Zones $zone)
    {
        foreach ($this->zones as $mapZone) {
            if ($mapZone->getId() === $zone->getId() ||
                $mapZone->getName() === $zone->getName()
            ) {
                return $mapZone;
            }
        }
    }

    /**
     * @return $this
     */
    public function setZone(Zones $zone)
    {
        $exists = $this->getZone($zone);
        if (!$exists) {
            $this->addZone($zone);
        } else {
            $this->zones->removeElement($exists);
            $this->addZone($zone);
        }

        return $this;
    }

    /**
     * Get zones.
     *
     * @return Zones[]|ArrayCollection
     *
     * @codeCoverageIgnore
     */
    public function getZones()
    {
        return $this->zones;
    }

    /**
     * Set nameSlug.
     *
     * @param string $nameSlug
     *
     * @return Map
     *
     * @codeCoverageIgnore
     */
    public function setNameSlug($nameSlug)
    {
        $this->nameSlug = $nameSlug;

        return $this;
    }

    /**
     * Get nameSlug.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getNameSlug()
    {
        return $this->nameSlug;
    }

    /**
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getStartZoom()
    {
        return $this->startZoom;
    }

    /**
     * @param int $startZoom
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setStartZoom($startZoom)
    {
        $this->startZoom = $startZoom;

        return $this;
    }

    /**
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getStartX()
    {
        return $this->startX;
    }

    /**
     * @param int $startX
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setStartX($startX)
    {
        $this->startX = $startX;

        return $this;
    }

    /**
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getStartY()
    {
        return $this->startY;
    }

    /**
     * @param int $startY
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setStartY($startY)
    {
        $this->startY = $startY;

        return $this;
    }

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getBounds()
    {
        return $this->bounds;
    }

    /**
     * @param string $bounds
     *
     * @return Map
     *
     * @codeCoverageIgnore
     */
    public function setBounds($bounds)
    {
        $this->bounds = $bounds;

        return $this;
    }

    /**
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getCoordinatesRatio()
    {
        return $this->coordinatesRatio;
    }

    /**
     * @param int $coordinatesRatio
     *
     * @return Map
     *
     * @codeCoverageIgnore
     */
    public function setCoordinatesRatio($coordinatesRatio)
    {
        $this->coordinatesRatio = $coordinatesRatio;

        return $this;
    }

    /**
     * Réinitialise correctement les informations de la map.
     */
    public function refresh()
    {
        foreach ($this->routes as $route) {
            $route->refresh();
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getJsonBounds()
    {
        return \json_decode($this->bounds, true);
    }

    /**
     * @return $this
     */
    public function setJsonBounds(array $bounds = [])
    {
        $this->bounds = \json_encode($bounds);

        return $this;
    }
}
