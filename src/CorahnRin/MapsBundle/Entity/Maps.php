<?php

namespace CorahnRin\MapsBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

use JMS\Serializer\Annotation\ExclusionPolicy as ExclusionPolicy;
use JMS\Serializer\Annotation\Expose as Expose;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as DoctrineCollection;

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Maps
 *
 * @ORM\Table(name="maps")
 * @ORM\Entity(repositoryClass="CorahnRin\MapsBundle\Repository\MapsRepository")
 * @ExclusionPolicy("all")
 */
class Maps
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Expose
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     * @Expose
     */
    protected $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     * @Expose
     */
    protected $nameSlug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $image;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Expose
     */
    protected $description;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint", options={"default":0})
     * @Assert\Range(
     *      min = 1,
     *      max = 10
     * )
     * @Expose
     */
    protected $maxZoom;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $updated;

	/**
     * @var DoctrineCollection
	 *
	 * @ORM\OneToMany(targetEntity="Routes", mappedBy="map")
	 */
	protected $routes;

	/**
     * @var DoctrineCollection
	 *
	 * @ORM\OneToMany(targetEntity="Markers", mappedBy="map")
	 */
	protected $markers;

	/**
     * @var DoctrineCollection
	 *
	 * @ORM\OneToMany(targetEntity="Zones", mappedBy="map", cascade={"persist"})
     * @Expose
	 */
	protected $zones;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=false,options={"default":0})
     */
    protected $deleted;

    public function __toString() {
        return $this->id.' - '.$this->name;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setMaxZoom(10);
        $this->routes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->markers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->zones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Maps
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Maps
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Maps
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set maxZoom
     *
     * @param boolean $maxZoom
     * @return Maps
     */
    public function setMaxZoom($maxZoom)
    {
        $this->maxZoom = $maxZoom;

        return $this;
    }

    /**
     * Get maxZoom
     *
     * @return boolean
     */
    public function getMaxZoom()
    {
        return $this->maxZoom;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Maps
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Maps
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Add routes
     *
     * @param \CorahnRin\MapsBundle\Entity\Routes $routes
     * @return Maps
     */
    public function addRoute(\CorahnRin\MapsBundle\Entity\Routes $routes)
    {
        $this->routes[] = $routes;

        return $this;
    }

    /**
     * Remove routes
     *
     * @param \CorahnRin\MapsBundle\Entity\Routes $routes
     */
    public function removeRoute(\CorahnRin\MapsBundle\Entity\Routes $routes)
    {
        $this->routes->removeElement($routes);
    }

    /**
     * Get routes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Get zone
     *
     * @return Zones
     */
    public function getRoute(\CorahnRin\MapsBundle\Entity\Routes $route)
    {
        foreach ($this->routes as $mapRoute) {
            if ($mapRoute->getId() === $route->getId() ||
                $mapRoute->getName() === $route->getName()) {
                return $mapRoute;
            }
        }
        return null;
    }

    /**
     *
     */
    public function setRoute(\CorahnRin\MapsBundle\Entity\Routes $route) {
        $exists = $this->getRoute($route);
        if (!$exists) {
            $this->addRoute($route);
        } else {
            $this->routes->removeElement($exists);
            $this->addRoute($route);
        }
    }

    /**
     * Add markers
     *
     * @param \CorahnRin\MapsBundle\Entity\Markers $markers
     * @return Maps
     */
    public function addMarker(\CorahnRin\MapsBundle\Entity\Markers $markers)
    {
        $this->markers[] = $markers;

        return $this;
    }

    /**
     * Remove markers
     *
     * @param \CorahnRin\MapsBundle\Entity\Markers $markers
     */
    public function removeMarker(\CorahnRin\MapsBundle\Entity\Markers $markers)
    {
        $this->markers->removeElement($markers);
    }

    /**
     * Get markers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMarkers()
    {
        return $this->markers;
    }

    /**
     * Get zone
     *
     * @return Zones
     */
    public function getMarker(\CorahnRin\MapsBundle\Entity\Markers $marker)
    {
        foreach ($this->markers as $mapMarker) {
            if ($mapMarker->getId() === $marker->getId() ||
                $mapMarker->getName() === $marker->getName()) {
                return $mapMarker;
            }
        }
        return null;
    }

    /**
     *
     */
    public function setMarker(\CorahnRin\MapsBundle\Entity\Markers $marker) {
        $exists = $this->getMarker($marker);
        if (!$exists) {
            $this->addMarker($marker);
        } else {
            $this->markers->removeElement($exists);
            $this->addMarker($marker);
        }
    }

    /**
     * Add zones
     *
     * @param \CorahnRin\MapsBundle\Entity\Zones $zones
     * @return Maps
     */
    public function addZone(\CorahnRin\MapsBundle\Entity\Zones $zones)
    {
        $this->zones[] = $zones;

        return $this;
    }

    /**
     * Remove zones
     *
     * @param \CorahnRin\MapsBundle\Entity\Zones $zones
     */
    public function removeZone(\CorahnRin\MapsBundle\Entity\Zones $zones)
    {
        $this->zones->removeElement($zones);
    }

    /**
     * Get zone
     *
     * @return Zones
     */
    public function getZone(\CorahnRin\MapsBundle\Entity\Zones $zone)
    {
        foreach ($this->zones as $mapZone) {
            if ($mapZone->getId() === $zone->getId() ||
                $mapZone->getName() === $zone->getName()) {
                return $mapZone;
            }
        }
        return null;
    }

    /**
     *
     */
    public function setZone(\CorahnRin\MapsBundle\Entity\Zones $zone) {
        $exists = $this->getZone($zone);
        if (!$exists) {
            $this->addZone($zone);
        } else {
            $this->zones->removeElement($exists);
            $this->addZone($zone);
        }
    }

    /**
     * Get zones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getZones()
    {
        return $this->zones;
    }

    /**
     * Set nameSlug
     *
     * @param string $nameSlug
     * @return Maps
     */
    public function setNameSlug($nameSlug)
    {
        $this->nameSlug = $nameSlug;

        return $this;
    }

    /**
     * Get nameSlug
     *
     * @return string
     */
    public function getNameSlug()
    {
        return $this->nameSlug;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     * @return Maps
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
}
