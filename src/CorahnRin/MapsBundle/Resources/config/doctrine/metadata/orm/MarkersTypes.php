<?php

namespace CorahnRin\MapsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as DoctrineCollection;

/**
 * MarkersType
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CorahnRin\MapsBundle\Repository\MarkersTypesRepository")
 */
class MarkersTypes
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    private $name;

    /**
     * @var \DateTime
     *
	 * @Gedmo\Mapping\Annotation\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var \DateTime
     *
	 * @Gedmo\Mapping\Annotation\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $updated;

    /**
     * @var DoctrineCollection
     *
     * @ORM\OneToMany(targetEntity="EventsMarkers", mappedBy="markerType")
     */
    private $events;

	/**
     * @var DoctrineCollection
	 *
	 * @ORM\OneToMany(targetEntity="Markers", mappedBy="markerType")
	 */
	private $markers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
        $this->markers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return MarkersTypes
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
     * Set created
     *
     * @param \DateTime $created
     * @return MarkersTypes
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
     * @return MarkersTypes
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
     * Add events
     *
     * @param \CorahnRin\MapsBundle\Entity\EventsMarkers $events
     * @return MarkersTypes
     */
    public function addEvent(\CorahnRin\MapsBundle\Entity\EventsMarkers $events)
    {
        $this->events[] = $events;
    
        return $this;
    }

    /**
     * Remove events
     *
     * @param \CorahnRin\MapsBundle\Entity\EventsMarkers $events
     */
    public function removeEvent(\CorahnRin\MapsBundle\Entity\EventsMarkers $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add markers
     *
     * @param \CorahnRin\MapsBundle\Entity\Markers $markers
     * @return MarkersTypes
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
}