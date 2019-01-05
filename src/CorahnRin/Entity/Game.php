<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use User\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="games", uniqueConstraints={@ORM\UniqueConstraint(name="idgUnique", columns={"name", "game_master_id"})})
 */
class Game
{
    /**
     * @var int
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
     * @ORM\Column(type="text", nullable=true)
     */
    protected $summary;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
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
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     * @ORM\JoinColumn(name="game_master_id", nullable=false)
     */
    protected $gameMaster;

    /**
     * @var Character[]
     *
     * @ORM\OneToMany(targetEntity="Character", mappedBy="game")
     */
    protected $characters;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    protected $deleted;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
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
     * @return Game
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
     * Set summary.
     *
     * @param string $summary
     *
     * @return Game
     *
     * @codeCoverageIgnore
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set gmNotes.
     *
     * @param string $gmNotes
     *
     * @return Game
     *
     * @codeCoverageIgnore
     */
    public function setGmNotes($gmNotes)
    {
        $this->gmNotes = $gmNotes;

        return $this;
    }

    /**
     * Get gmNotes.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getGmNotes()
    {
        return $this->gmNotes;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return Game
     *
     * @codeCoverageIgnore
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime
     *
     * @codeCoverageIgnore
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated.
     *
     * @param \DateTime $updated
     *
     * @return Game
     *
     * @codeCoverageIgnore
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated.
     *
     * @return \DateTime
     *
     * @codeCoverageIgnore
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set gameMaster.
     *
     * @param User $gameMaster
     *
     * @return Game
     *
     * @codeCoverageIgnore
     */
    public function setGameMaster(User $gameMaster = null)
    {
        $this->gameMaster = $gameMaster;

        return $this;
    }

    /**
     * Get gameMaster.
     *
     * @return User
     *
     * @codeCoverageIgnore
     */
    public function getGameMaster()
    {
        return $this->gameMaster;
    }

    /**
     * Add characters.
     *
     *
     * @return Game
     */
    public function addCharacter(Character $characters)
    {
        $this->characters[] = $characters;

        return $this;
    }

    /**
     * Remove characters.
     */
    public function removeCharacter(Character $characters)
    {
        $this->characters->removeElement($characters);
    }

    /**
     * Get characters.
     *
     * @return \Doctrine\Common\Collections\Collection
     *
     * @codeCoverageIgnore
     */
    public function getCharacters()
    {
        return $this->characters;
    }

    /**
     * Set deleted.
     *
     * @param \DateTime $deleted
     *
     * @return Game
     *
     * @codeCoverageIgnore
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted.
     *
     * @return \DateTime
     *
     * @codeCoverageIgnore
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
}
