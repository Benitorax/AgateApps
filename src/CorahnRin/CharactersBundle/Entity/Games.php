<?php

namespace CorahnRin\CharactersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Games
 *
 * @ORM\Entity(repositoryClass="CorahnRin\CharactersBundle\Repository\GamesRepository")
 * @ORM\Table(name="games", uniqueConstraints={@ORM\UniqueConstraint(name="idgUnique", columns={"name", "gameMaster_id"})})
 */
class Games
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=140, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(columnDefinition="TEXT")
     */
    protected $summary;

    /**
     * @var string
     *
     * @ORM\Column(columnDefinition="TEXT")
     */
    protected $gmNotes;

    /**
     * @var \Datetime
     * @Gedmo\Mapping\Annotation\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $created;

    /**
     * @var \Datetime

     * @Gedmo\Mapping\Annotation\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $updated;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\UsersBundle\Entity\Users")
     */
    protected $gameMaster;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Characters", mappedBy="game")
     */
    protected $characters;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=false,options={"default":0})
     */
    protected $deleted;

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
     * @return Games
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
     * Set summary
     *
     * @param string $summary
     * @return Games
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set gmNotes
     *
     * @param string $gmNotes
     * @return Games
     */
    public function setGmNotes($gmNotes)
    {
        $this->gmNotes = $gmNotes;

        return $this;
    }

    /**
     * Get gmNotes
     *
     * @return string
     */
    public function getGmNotes()
    {
        return $this->gmNotes;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Games
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
     * @return Games
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
     * Set gameMaster
     *
     * @param \CorahnRin\UsersBundle\Entity\Users $gameMaster
     * @return Games
     */
    public function setGameMaster(\CorahnRin\UsersBundle\Entity\Users $gameMaster = null)
    {
        $this->gameMaster = $gameMaster;

        return $this;
    }

    /**
     * Get gameMaster
     *
     * @return \CorahnRin\UsersBundle\Entity\Users
     */
    public function getGameMaster()
    {
        return $this->gameMaster;
    }

    /**
     * Add characters
     *
     * @param \CorahnRin\CharactersBundle\Entity\Characters $characters
     * @return Games
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

    /**
     * Set deleted
     *
     * @param boolean $deleted
     * @return Games
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
