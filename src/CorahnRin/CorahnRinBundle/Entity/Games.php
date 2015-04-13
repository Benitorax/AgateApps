<?php

namespace CorahnRin\CorahnRinBundle\Entity;

use Esteren\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Games
 *
 * @Gedmo\SoftDeleteable(fieldName="deleted")
 * @ORM\Entity()
 * @ORM\Table(name="games", uniqueConstraints={@ORM\UniqueConstraint(name="idgUnique", columns={"name", "gameMaster_id"})})
 */
class Games {

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
     * @ORM\Column(type="text",nullable=true)
     */
    protected $summary;

    /**
     * @var string
     *
     * @ORM\Column(type="text",nullable=true)
     */
    protected $gmNotes;

    /**
     * @var \Datetime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $created;

    /**
     * @var \Datetime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $updated;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Esteren\UserBundle\Entity\User")
     */
    protected $gameMaster;

    /**
     * @var Characters[]
     *
     * @ORM\OneToMany(targetEntity="Characters", mappedBy="game")
     */
    protected $characters;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    protected $deleted = null;

    /**
     * Constructor
     */
    public function __construct() {
        $this->characters = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return $this
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Games
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return Games
     */
    public function setSummary($summary) {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary() {
        return $this->summary;
    }

    /**
     * Set gmNotes
     *
     * @param string $gmNotes
     * @return Games
     */
    public function setGmNotes($gmNotes) {
        $this->gmNotes = $gmNotes;

        return $this;
    }

    /**
     * Get gmNotes
     *
     * @return string
     */
    public function getGmNotes() {
        return $this->gmNotes;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Games
     */
    public function setCreated($created) {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Games
     */
    public function setUpdated($updated) {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated() {
        return $this->updated;
    }

    /**
     * Set gameMaster
     *
     * @param User $gameMaster
     * @return Games
     */
    public function setGameMaster(User $gameMaster = null) {
        $this->gameMaster = $gameMaster;

        return $this;
    }

    /**
     * Get gameMaster
     *
     * @return User
     */
    public function getGameMaster() {
        return $this->gameMaster;
    }

    /**
     * Add characters
     *
     * @param Characters $characters
     * @return Games
     */
    public function addCharacter(Characters $characters) {
        $this->characters[] = $characters;

        return $this;
    }

    /**
     * Remove characters
     *
     * @param Characters $characters
     */
    public function removeCharacter(Characters $characters) {
        $this->characters->removeElement($characters);
    }

    /**
     * Get characters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCharacters() {
        return $this->characters;
    }

    /**
     * Set deleted
     *
     * @param \DateTime $deleted
     * @return Games
     */
    public function setDeleted($deleted) {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return \DateTime
     */
    public function getDeleted() {
        return $this->deleted;
    }
}