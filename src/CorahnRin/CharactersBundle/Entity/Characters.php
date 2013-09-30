<?php

namespace CorahnRin\CharactersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Characters
 *
 * @ORM\Entity
 */
class Characters
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
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1, nullable=false)
     */
    private $sex;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=false)
     */
    private $inventory;

    /**
     * @var \CorahnRin\CharactersBundle\Classes\Money
     *
     * @ORM\Column(type="object", nullable=false)
     */
    private $money;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=false)
     */
    private $orientation;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=25, nullable=false)
     */
    private $geoLiving;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $age;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $mentalResist;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=false)
     */
    private $health;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $stamina;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $survival;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $speed;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $defense;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $rindath;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $exaltation;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $experienceActual;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $experienceSpent;

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
     * @ORM\ManyToMany(targetEntity="Armors")
     */
    private $armors;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Artifacts")
     */
    private $artifacts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Miracles", mappedBy="characters")
     */
    private $miracles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Ogham", inversedBy="characters")
     */
    private $ogham;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Weapons", inversedBy="characters")
     */
    private $weapons;

    /**
     * @var \CharSocialClass
     *
     * @ORM\OneToOne(targetEntity="CharSocialClass", mappedBy="character")
     */
    private $socialClass;

    /**
     * @var \Disorders
     *
     * @ORM\ManyToOne(targetEntity="Disorders")
     */
    private $disorder;

    /**
     * @var \Jobs
     *
     * @ORM\ManyToOne(targetEntity="Jobs")
     */
    private $job;

    /**
     * @var \Regions
     *
     * @ORM\ManyToOne(targetEntity="Regions")
     */
    private $region;

    /**
     * @var \Traits
     * @ORM\ManyToOne(targetEntity="Traits")
     */
    private $traitFlaw;

    /**
     * @var \Traits
     * @ORM\ManyToOne(targetEntity="Traits")
     */
    private $traitQuality;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="CharAvtgs", mappedBy="character")
     */
    private $avantages;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="CharDomains", mappedBy="character")
     */
    private $domains;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="CharDisciplines", mappedBy="character")
     */
    private $disciplines;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="CharFlux", mappedBy="character")
     */
    private $flux;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="CharModifications", mappedBy="character")
     */
    private $modifications;

    /**
     * @var \Users
     * @ORM\ManyToOne(targetEntity="CorahnRin\UsersBundle\Entity\Users", inversedBy="characters")
     */
    private $user;
	
	private $baseChar;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->armors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->artifacts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->miracles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ogham = new \Doctrine\Common\Collections\ArrayCollection();
        $this->weapons = new \Doctrine\Common\Collections\ArrayCollection();
        $this->avantages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->domains = new \Doctrine\Common\Collections\ArrayCollection();
        $this->disciplines = new \Doctrine\Common\Collections\ArrayCollection();
        $this->flux = new \Doctrine\Common\Collections\ArrayCollection();
        $this->modifications = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Characters
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
     * Set status
     *
     * @param boolean $status
     * @return Characters
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set sex
     *
     * @param string $sex
     * @return Characters
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    
        return $this;
    }

    /**
     * Get sex
     *
     * @return string 
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set inventory
     *
     * @param array $inventory
     * @return Characters
     */
    public function setInventory($inventory)
    {
        $this->inventory = $inventory;
    
        return $this;
    }

    /**
     * Get inventory
     *
     * @return array 
     */
    public function getInventory()
    {
        return $this->inventory;
    }

    /**
     * Set money
     *
     * @param \stdClass $money
     * @return Characters
     */
    public function setMoney($money)
    {
        $this->money = $money;
    
        return $this;
    }

    /**
     * Get money
     *
     * @return \stdClass 
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * Set orientation
     *
     * @param string $orientation
     * @return Characters
     */
    public function setOrientation($orientation)
    {
        $this->orientation = $orientation;
    
        return $this;
    }

    /**
     * Get orientation
     *
     * @return string 
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * Set geoLiving
     *
     * @param string $geoLiving
     * @return Characters
     */
    public function setGeoLiving($geoLiving)
    {
        $this->geoLiving = $geoLiving;
    
        return $this;
    }

    /**
     * Get geoLiving
     *
     * @return string 
     */
    public function getGeoLiving()
    {
        return $this->geoLiving;
    }

    /**
     * Set age
     *
     * @param integer $age
     * @return Characters
     */
    public function setAge($age)
    {
        $this->age = $age;
    
        return $this;
    }

    /**
     * Get age
     *
     * @return integer 
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set mentalResist
     *
     * @param integer $mentalResist
     * @return Characters
     */
    public function setMentalResist($mentalResist)
    {
        $this->mentalResist = $mentalResist;
    
        return $this;
    }

    /**
     * Get mentalResist
     *
     * @return integer 
     */
    public function getMentalResist()
    {
        return $this->mentalResist;
    }

    /**
     * Set health
     *
     * @param array $health
     * @return Characters
     */
    public function setHealth($health)
    {
        $this->health = $health;
    
        return $this;
    }

    /**
     * Get health
     *
     * @return array 
     */
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * Set stamina
     *
     * @param integer $stamina
     * @return Characters
     */
    public function setStamina($stamina)
    {
        $this->stamina = $stamina;
    
        return $this;
    }

    /**
     * Get stamina
     *
     * @return integer 
     */
    public function getStamina()
    {
        return $this->stamina;
    }

    /**
     * Set survival
     *
     * @param boolean $survival
     * @return Characters
     */
    public function setSurvival($survival)
    {
        $this->survival = $survival;
    
        return $this;
    }

    /**
     * Get survival
     *
     * @return boolean 
     */
    public function getSurvival()
    {
        return $this->survival;
    }

    /**
     * Set speed
     *
     * @param integer $speed
     * @return Characters
     */
    public function setSpeed($speed)
    {
        $this->speed = $speed;
    
        return $this;
    }

    /**
     * Get speed
     *
     * @return integer 
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * Set defense
     *
     * @param integer $defense
     * @return Characters
     */
    public function setDefense($defense)
    {
        $this->defense = $defense;
    
        return $this;
    }

    /**
     * Get defense
     *
     * @return integer 
     */
    public function getDefense()
    {
        return $this->defense;
    }

    /**
     * Set rindath
     *
     * @param integer $rindath
     * @return Characters
     */
    public function setRindath($rindath)
    {
        $this->rindath = $rindath;
    
        return $this;
    }

    /**
     * Get rindath
     *
     * @return integer 
     */
    public function getRindath()
    {
        return $this->rindath;
    }

    /**
     * Set exaltation
     *
     * @param integer $exaltation
     * @return Characters
     */
    public function setExaltation($exaltation)
    {
        $this->exaltation = $exaltation;
    
        return $this;
    }

    /**
     * Get exaltation
     *
     * @return integer 
     */
    public function getExaltation()
    {
        return $this->exaltation;
    }

    /**
     * Set experienceActual
     *
     * @param integer $experienceActual
     * @return Characters
     */
    public function setExperienceActual($experienceActual)
    {
        $this->experienceActual = $experienceActual;
    
        return $this;
    }

    /**
     * Get experienceActual
     *
     * @return integer 
     */
    public function getExperienceActual()
    {
        return $this->experienceActual;
    }

    /**
     * Set experienceSpent
     *
     * @param integer $experienceSpent
     * @return Characters
     */
    public function setExperienceSpent($experienceSpent)
    {
        $this->experienceSpent = $experienceSpent;
    
        return $this;
    }

    /**
     * Get experienceSpent
     *
     * @return integer 
     */
    public function getExperienceSpent()
    {
        return $this->experienceSpent;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Characters
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
     * @return Characters
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
     * Add armors
     *
     * @param \CorahnRin\CharactersBundle\Entity\Armors $armors
     * @return Characters
     */
    public function addArmor(\CorahnRin\CharactersBundle\Entity\Armors $armors)
    {
        $this->armors[] = $armors;
    
        return $this;
    }

    /**
     * Remove armors
     *
     * @param \CorahnRin\CharactersBundle\Entity\Armors $armors
     */
    public function removeArmor(\CorahnRin\CharactersBundle\Entity\Armors $armors)
    {
        $this->armors->removeElement($armors);
    }

    /**
     * Get armors
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArmors()
    {
        return $this->armors;
    }

    /**
     * Add artifacts
     *
     * @param \CorahnRin\CharactersBundle\Entity\Artifacts $artifacts
     * @return Characters
     */
    public function addArtifact(\CorahnRin\CharactersBundle\Entity\Artifacts $artifacts)
    {
        $this->artifacts[] = $artifacts;
    
        return $this;
    }

    /**
     * Remove artifacts
     *
     * @param \CorahnRin\CharactersBundle\Entity\Artifacts $artifacts
     */
    public function removeArtifact(\CorahnRin\CharactersBundle\Entity\Artifacts $artifacts)
    {
        $this->artifacts->removeElement($artifacts);
    }

    /**
     * Get artifacts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArtifacts()
    {
        return $this->artifacts;
    }

    /**
     * Add miracles
     *
     * @param \CorahnRin\CharactersBundle\Entity\Miracles $miracles
     * @return Characters
     */
    public function addMiracle(\CorahnRin\CharactersBundle\Entity\Miracles $miracles)
    {
        $this->miracles[] = $miracles;
    
        return $this;
    }

    /**
     * Remove miracles
     *
     * @param \CorahnRin\CharactersBundle\Entity\Miracles $miracles
     */
    public function removeMiracle(\CorahnRin\CharactersBundle\Entity\Miracles $miracles)
    {
        $this->miracles->removeElement($miracles);
    }

    /**
     * Get miracles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMiracles()
    {
        return $this->miracles;
    }

    /**
     * Add ogham
     *
     * @param \CorahnRin\CharactersBundle\Entity\Ogham $ogham
     * @return Characters
     */
    public function addOgham(\CorahnRin\CharactersBundle\Entity\Ogham $ogham)
    {
        $this->ogham[] = $ogham;
    
        return $this;
    }

    /**
     * Remove ogham
     *
     * @param \CorahnRin\CharactersBundle\Entity\Ogham $ogham
     */
    public function removeOgham(\CorahnRin\CharactersBundle\Entity\Ogham $ogham)
    {
        $this->ogham->removeElement($ogham);
    }

    /**
     * Get ogham
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOgham()
    {
        return $this->ogham;
    }

    /**
     * Add weapons
     *
     * @param \CorahnRin\CharactersBundle\Entity\Weapons $weapons
     * @return Characters
     */
    public function addWeapon(\CorahnRin\CharactersBundle\Entity\Weapons $weapons)
    {
        $this->weapons[] = $weapons;
    
        return $this;
    }

    /**
     * Remove weapons
     *
     * @param \CorahnRin\CharactersBundle\Entity\Weapons $weapons
     */
    public function removeWeapon(\CorahnRin\CharactersBundle\Entity\Weapons $weapons)
    {
        $this->weapons->removeElement($weapons);
    }

    /**
     * Get weapons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWeapons()
    {
        return $this->weapons;
    }

    /**
     * Set socialClass
     *
     * @param \CorahnRin\CharactersBundle\Entity\CharSocialClass $socialClass
     * @return Characters
     */
    public function setSocialClass(\CorahnRin\CharactersBundle\Entity\CharSocialClass $socialClass = null)
    {
        $this->socialClass = $socialClass;
    
        return $this;
    }

    /**
     * Get socialClass
     *
     * @return \CorahnRin\CharactersBundle\Entity\CharSocialClass 
     */
    public function getSocialClass()
    {
        return $this->socialClass;
    }

    /**
     * Set disorder
     *
     * @param \CorahnRin\CharactersBundle\Entity\Disorders $disorder
     * @return Characters
     */
    public function setDisorder(\CorahnRin\CharactersBundle\Entity\Disorders $disorder = null)
    {
        $this->disorder = $disorder;
    
        return $this;
    }

    /**
     * Get disorder
     *
     * @return \CorahnRin\CharactersBundle\Entity\Disorders 
     */
    public function getDisorder()
    {
        return $this->disorder;
    }

    /**
     * Set job
     *
     * @param \CorahnRin\CharactersBundle\Entity\Jobs $job
     * @return Characters
     */
    public function setJob(\CorahnRin\CharactersBundle\Entity\Jobs $job = null)
    {
        $this->job = $job;
    
        return $this;
    }

    /**
     * Get job
     *
     * @return \CorahnRin\CharactersBundle\Entity\Jobs 
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set region
     *
     * @param \CorahnRin\CharactersBundle\Entity\Regions $region
     * @return Characters
     */
    public function setRegion(\CorahnRin\CharactersBundle\Entity\Regions $region = null)
    {
        $this->region = $region;
    
        return $this;
    }

    /**
     * Get region
     *
     * @return \CorahnRin\CharactersBundle\Entity\Regions 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set traitFlaw
     *
     * @param \CorahnRin\CharactersBundle\Entity\Traits $traitFlaw
     * @return Characters
     */
    public function setTraitFlaw(\CorahnRin\CharactersBundle\Entity\Traits $traitFlaw = null)
    {
        $this->traitFlaw = $traitFlaw;
    
        return $this;
    }

    /**
     * Get traitFlaw
     *
     * @return \CorahnRin\CharactersBundle\Entity\Traits 
     */
    public function getTraitFlaw()
    {
        return $this->traitFlaw;
    }

    /**
     * Set traitQuality
     *
     * @param \CorahnRin\CharactersBundle\Entity\Traits $traitQuality
     * @return Characters
     */
    public function setTraitQuality(\CorahnRin\CharactersBundle\Entity\Traits $traitQuality = null)
    {
        $this->traitQuality = $traitQuality;
    
        return $this;
    }

    /**
     * Get traitQuality
     *
     * @return \CorahnRin\CharactersBundle\Entity\Traits 
     */
    public function getTraitQuality()
    {
        return $this->traitQuality;
    }

    /**
     * Add avantages
     *
     * @param \CorahnRin\CharactersBundle\Entity\CharAvtgs $avantages
     * @return Characters
     */
    public function addAvantage(\CorahnRin\CharactersBundle\Entity\CharAvtgs $avantages)
    {
        $this->avantages[] = $avantages;
    
        return $this;
    }

    /**
     * Remove avantages
     *
     * @param \CorahnRin\CharactersBundle\Entity\CharAvtgs $avantages
     */
    public function removeAvantage(\CorahnRin\CharactersBundle\Entity\CharAvtgs $avantages)
    {
        $this->avantages->removeElement($avantages);
    }

    /**
     * Get avantages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAvantages()
    {
        return $this->avantages;
    }

    /**
     * Add domains
     *
     * @param \CorahnRin\CharactersBundle\Entity\CharDomains $domains
     * @return Characters
     */
    public function addDomain(\CorahnRin\CharactersBundle\Entity\CharDomains $domains)
    {
        $this->domains[] = $domains;
    
        return $this;
    }

    /**
     * Remove domains
     *
     * @param \CorahnRin\CharactersBundle\Entity\CharDomains $domains
     */
    public function removeDomain(\CorahnRin\CharactersBundle\Entity\CharDomains $domains)
    {
        $this->domains->removeElement($domains);
    }

    /**
     * Get domains
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDomains()
    {
        return $this->domains;
    }

    /**
     * Add disciplines
     *
     * @param \CorahnRin\CharactersBundle\Entity\CharDisciplines $disciplines
     * @return Characters
     */
    public function addDiscipline(\CorahnRin\CharactersBundle\Entity\CharDisciplines $disciplines)
    {
        $this->disciplines[] = $disciplines;
    
        return $this;
    }

    /**
     * Remove disciplines
     *
     * @param \CorahnRin\CharactersBundle\Entity\CharDisciplines $disciplines
     */
    public function removeDiscipline(\CorahnRin\CharactersBundle\Entity\CharDisciplines $disciplines)
    {
        $this->disciplines->removeElement($disciplines);
    }

    /**
     * Get disciplines
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDisciplines()
    {
        return $this->disciplines;
    }

    /**
     * Add flux
     *
     * @param \CorahnRin\CharactersBundle\Entity\CharFlux $flux
     * @return Characters
     */
    public function addFlux(\CorahnRin\CharactersBundle\Entity\CharFlux $flux)
    {
        $this->flux[] = $flux;
    
        return $this;
    }

    /**
     * Remove flux
     *
     * @param \CorahnRin\CharactersBundle\Entity\CharFlux $flux
     */
    public function removeFlux(\CorahnRin\CharactersBundle\Entity\CharFlux $flux)
    {
        $this->flux->removeElement($flux);
    }

    /**
     * Get flux
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFlux()
    {
        return $this->flux;
    }

    /**
     * Add modifications
     *
     * @param \CorahnRin\CharactersBundle\Entity\CharModifications $modifications
     * @return Characters
     */
    public function addModification(\CorahnRin\CharactersBundle\Entity\CharModifications $modifications)
    {
        $this->modifications[] = $modifications;
    
        return $this;
    }

    /**
     * Remove modifications
     *
     * @param \CorahnRin\CharactersBundle\Entity\CharModifications $modifications
     */
    public function removeModification(\CorahnRin\CharactersBundle\Entity\CharModifications $modifications)
    {
        $this->modifications->removeElement($modifications);
    }

    /**
     * Get modifications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModifications()
    {
        return $this->modifications;
    }

    /**
     * Set user
     *
     * @param \CorahnRin\UsersBundle\Entity\Users $user
     * @return Characters
     */
    public function setUser(\CorahnRin\UsersBundle\Entity\Users $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \CorahnRin\UsersBundle\Entity\Users 
     */
    public function getUser()
    {
        return $this->user;
    }
}