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

use CorahnRin\Entity\Book;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Table(name="maps_factions")
 * @ORM\Entity(repositoryClass="EsterenMaps\Repository\FactionsRepository")
 */
class Faction
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
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var Zone[]
     * @ORM\OneToMany(targetEntity="EsterenMaps\Entity\Zone", mappedBy="faction")
     */
    protected $zones;

    /**
     * @var Route[]
     * @ORM\OneToMany(targetEntity="EsterenMaps\Entity\Route", mappedBy="faction")
     */
    protected $routes;

    /**
     * @var Marker[]
     * @ORM\OneToMany(targetEntity="EsterenMaps\Entity\Marker", mappedBy="faction")
     */
    protected $markers;

    /**
     * @var Book
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Book")
     * @ORM\JoinColumn(name="book_id", nullable=false)
     */
    protected $book;

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->zones = new ArrayCollection();
        $this->routes = new ArrayCollection();
        $this->markers = new ArrayCollection();
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
     * @return Faction
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
     * @return Faction
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
     * Add routes.
     *
     *
     * @return Faction
     */
    public function addRoute(Route $routes)
    {
        $this->routes[] = $routes;

        return $this;
    }

    /**
     * Remove routes.
     */
    public function removeRoute(Route $routes)
    {
        $this->routes->removeElement($routes);
    }

    /**
     * Get routes.
     *
     * @return Route[]
     *
     * @codeCoverageIgnore
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Add markers.
     *
     *
     * @return Faction
     */
    public function addMarker(Marker $markers)
    {
        $this->markers[] = $markers;

        return $this;
    }

    /**
     * Remove markers.
     */
    public function removeMarker(Marker $markers)
    {
        $this->markers->removeElement($markers);
    }

    /**
     * Get markers.
     *
     * @return Marker[]
     *
     * @codeCoverageIgnore
     */
    public function getMarkers()
    {
        return $this->markers;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Faction
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
     * Set book.
     *
     * @param Book $book
     *
     * @return Faction
     *
     * @codeCoverageIgnore
     */
    public function setBook(Book $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book.
     *
     * @return Book
     *
     * @codeCoverageIgnore
     */
    public function getBook()
    {
        return $this->book;
    }
}
