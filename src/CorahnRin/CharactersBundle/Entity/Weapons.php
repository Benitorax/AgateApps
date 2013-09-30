<?php

namespace CorahnRin\CharactersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Weapons
 *
 * @ORM\Entity
 */
class Weapons
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false, unique=true)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $dmg;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3, nullable=false)
     */
    private $availability;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $contact;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $range;

    /**
     * @var \Datetime
     * @Gedmo\Mapping\Annotation\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var \Datetime

     * @Gedmo\Mapping\Annotation\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $updated;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Characters", mappedBy="weapons")
     */
    private $characters;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->characters = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Weapons
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
     * Set dmg
     *
     * @param boolean $dmg
     * @return Weapons
     */
    public function setDmg($dmg)
    {
        $this->dmg = $dmg;
    
        return $this;
    }

    /**
     * Get dmg
     *
     * @return boolean 
     */
    public function getDmg()
    {
        return $this->dmg;
    }

    /**
     * Set price
     *
     * @param integer $price
     * @return Weapons
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return integer 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set availability
     *
     * @param string $availability
     * @return Weapons
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;
    
        return $this;
    }

    /**
     * Get availability
     *
     * @return string 
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * Set contact
     *
     * @param boolean $contact
     * @return Weapons
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    
        return $this;
    }

    /**
     * Get contact
     *
     * @return boolean 
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set range
     *
     * @param integer $range
     * @return Weapons
     */
    public function setRange($range)
    {
        $this->range = $range;
    
        return $this;
    }

    /**
     * Get range
     *
     * @return integer 
     */
    public function getRange()
    {
        return $this->range;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Weapons
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
     * @return Weapons
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
     * Add characters
     *
     * @param \CorahnRin\CharactersBundle\Entity\Characters $characters
     * @return Weapons
     */
    public function addCharacter(\CorahnRin\CharactersBundle\Entity\Characters $characters)
    {
        $this->characters[] = $characters;
    
        return $this;
    }

    /**
     * Remove characters
     *
     * @param \CorahnRin\CharactersBundle\Entity\Characters $characters
     */
    public function removeCharacter(\CorahnRin\CharactersBundle\Entity\Characters $characters)
    {
        $this->characters->removeElement($characters);
    }

    /**
     * Get characters
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCharacters()
    {
        return $this->characters;
    }
}