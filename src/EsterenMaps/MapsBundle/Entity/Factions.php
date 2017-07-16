<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\MapsBundle\Entity;

use CorahnRin\CorahnRinBundle\Entity\Books;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * Factions.
 *
 * @ORM\Table(name="maps_factions")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @ORM\Entity()
 */
class Factions
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    /**
     * @var int
     *
     * @ORM\Id()
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
     * @var Zones[]
     * @ORM\OneToMany(targetEntity="Zones", mappedBy="faction")
     */
    protected $zones;

    /**
     * @var Routes[]
     * @ORM\OneToMany(targetEntity="Routes", mappedBy="faction")
     */
    protected $routes;

    /**
     * @var Markers[]
     * @ORM\OneToMany(targetEntity="Markers", mappedBy="faction")
     */
    protected $markers;

    /**
     * @var Books
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\CorahnRinBundle\Entity\Books")
     * @ORM\JoinColumn(name="book_id", nullable=false)
     */
    protected $book;

    public function __toString()
    {
        return $this->id.' - '.$this->name;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->zones   = new ArrayCollection();
        $this->routes  = new ArrayCollection();
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
     * @return Factions
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
     * @param Zones $zones
     *
     * @return Factions
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
     * Add routes.
     *
     * @param Routes $routes
     *
     * @return Factions
     */
    public function addRoute(Routes $routes)
    {
        $this->routes[] = $routes;

        return $this;
    }

    /**
     * Remove routes.
     *
     * @param Routes $routes
     */
    public function removeRoute(Routes $routes)
    {
        $this->routes->removeElement($routes);
    }

    /**
     * Get routes.
     *
     * @return Routes[]
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
     * @param Markers $markers
     *
     * @return Factions
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
     * @return Factions
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
     * @param Books $book
     *
     * @return Factions
     *
     * @codeCoverageIgnore
     */
    public function setBook(Books $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book.
     *
     * @return Books
     *
     * @codeCoverageIgnore
     */
    public function getBook()
    {
        return $this->book;
    }
}
