<?php


namespace EsterenMaps\MapsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation\ExclusionPolicy as ExclusionPolicy;
use JMS\Serializer\Annotation\Expose as Expose;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Maps
 *
 * @ORM\Table(name="maps_transports_types")
 * @Gedmo\SoftDeleteable(fieldName="deleted")
 * @ORM\Entity(repositoryClass="EsterenMaps\MapsBundle\Repository\MapsRepository")
 * @ExclusionPolicy("all")
 */
class TransportTypes
{

    use TimestampableEntity;

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
     * @ORM\Column(name="name", type="string", length=255, nullable=false, unique=true)
     * @Expose
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255, nullable=false, unique=true)
     * @Expose
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Expose
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="speed", type="decimal", scale=4, precision=8)
     * @Assert\NotNull()
     * @Assert\Range(max="10000", min="-10000")
     * @Expose
     */
    protected $speed;
    /**
     * @var \Datetime
     *
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    protected $deleted = null;

    public function __toString()
    {
        return $this->id.' - '.$this->name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return TransportTypes
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return TransportTypes
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return TransportTypes
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return TransportTypes
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @param string $speed
     *
     * @return TransportTypes
     */
    public function setSpeed($speed)
    {
        $this->speed = $speed;
        return $this;
    }

    /**
     * @return \Datetime
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param \Datetime $deleted
     *
     * @return TransportTypes
     */
    public function setDeleted(\Datetime $deleted = null)
    {
        $this->deleted = $deleted;
        return $this;
    }

}