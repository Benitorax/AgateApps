<?php

namespace EsterenMaps\MapsBundle\Entity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as DoctrineCollection;

/**
 * EventsMarkersTypes
 *
 * @ORM\Table(name="events_markers_types")
 * @Gedmo\SoftDeleteable(fieldName="deleted")
 * @ORM\Entity(repositoryClass="EsterenMaps\MapsBundle\Repository\EventsMarkersTypesRepository")
 */
class EventsMarkersTypes {

    /**
     * @var Events
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Events", inversedBy="markersTypes")
     */
    protected $event;

    /**
     * @var MarkersTypes
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="MarkersTypes", inversedBy="events")
     */
    protected $markerType;

    /**
     * @var \Datetime
     *
	 * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $created;

    /**
     * @var \Datetime
     *
	 * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $updated;

	/**
	 * @var smallint
	 *
	 * @ORM\Column(type="smallint")
	 */
	protected $percentage;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    protected $deleted = null;

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return EventsMarkersTypes
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
     * @return EventsMarkersTypes
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
     * Set percentage
     *
     * @param integer $percentage
     * @return EventsMarkersTypes
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;

        return $this;
    }

    /**
     * Get percentage
     *
     * @return integer
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * Set event
     *
     * @param \EsterenMaps\MapsBundle\Entity\Events $event
     * @return EventsMarkersTypes
     */
    public function setEvent(\EsterenMaps\MapsBundle\Entity\Events $event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \EsterenMaps\MapsBundle\Entity\Events
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set markerType
     *
     * @param \EsterenMaps\MapsBundle\Entity\MarkersTypes $markerType
     * @return EventsMarkersTypes
     */
    public function setMarkerType(\EsterenMaps\MapsBundle\Entity\MarkersTypes $markerType)
    {
        $this->markerType = $markerType;

        return $this;
    }

    /**
     * Get markerType
     *
     * @return \EsterenMaps\MapsBundle\Entity\MarkersTypes
     */
    public function getMarkerType()
    {
        return $this->markerType;
    }
}
