<?php

namespace CorahnRin\CorahnRinBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Disciplines.
 *
 * @ORM\Table(name="disciplines")
 * @Gedmo\SoftDeleteable(fieldName="deleted")
 * @ORM\Entity()
 */
class Disciplines
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
     * @ORM\Column(type="string", length=50, nullable=false, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text",nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=40)
     */
    protected $rank;

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
     * @var bool
     *
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    protected $deleted = null;

    /**
     * @var Books
     * @ORM\ManyToOne(targetEntity="Books", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $book;

    /**
     * @var Domains[]
     *
     * @ORM\ManyToMany(targetEntity="Domains", inversedBy="disciplines")
     * @ORM\JoinTable(name="disciplines_domains",
     *      joinColumns={@ORM\JoinColumn(name="discipline_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="domain_id", referencedColumnName="id")}
     *  )
     */
    protected $domains;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->domains = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
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
     * @return Disciplines
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
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Disciplines
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
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set rank.
     *
     * @param string $rank
     *
     * @return Disciplines
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank.
     *
     * @return string
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return Disciplines
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
     * @return Disciplines
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
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set book.
     *
     * @param Books $book
     *
     * @return Disciplines
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
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Add domains.
     *
     * @param Domains $domains
     *
     * @return Disciplines
     */
    public function addDomain(Domains $domains)
    {
        $this->domains[] = $domains;

        return $this;
    }

    /**
     * Remove domains.
     *
     * @param Domains $domains
     */
    public function removeDomain(Domains $domains)
    {
        $this->domains->removeElement($domains);
    }

    /**
     * Get domains.
     *
     * @return Domains
     */
    public function getDomains()
    {
        return $this->domains;
    }

    /**
     * Set deleted.
     *
     * @param \DateTime $deleted
     *
     * @return Disciplines
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
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
}
