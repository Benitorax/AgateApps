<?php

namespace CorahnRin\CharactersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CharModifications
 *
 * @ORM\Entity
 */
class CharModifications
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
     * @ORM\Column(type="text", nullable=false)
     */
    private $contentBefore;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $contentAfter;

    /**
     * @var \Datetime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $dateCreated;

    /**
     * @var \Datetime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $dateUpdated;

    /**
     * @var \Characters
     *
     * @ORM\ManyToOne(targetEntity="Characters")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(referencedColumnName="id")
     * })
     */
    private $character;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\UsersBundle\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(referencedColumnName="id")
     * })
     */
    private $user;


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
     * Set contentBefore
     *
     * @param string $contentBefore
     * @return CharModifications
     */
    public function setContentBefore($contentBefore)
    {
        $this->contentBefore = $contentBefore;
    
        return $this;
    }

    /**
     * Get contentBefore
     *
     * @return string 
     */
    public function getContentBefore()
    {
        return $this->contentBefore;
    }

    /**
     * Set contentAfter
     *
     * @param string $contentAfter
     * @return CharModifications
     */
    public function setContentAfter($contentAfter)
    {
        $this->contentAfter = $contentAfter;
    
        return $this;
    }

    /**
     * Get contentAfter
     *
     * @return string 
     */
    public function getContentAfter()
    {
        return $this->contentAfter;
    }

    /**
     * Set dateCreated
     *
     * @param integer $dateCreated
     * @return CharModifications
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    
        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return integer 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateUpdated
     *
     * @param integer $dateUpdated
     * @return CharModifications
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;
    
        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return integer 
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * Set character
     *
     * @param \CorahnRin\CharactersBundle\Entity\Characters $character
     * @return CharModifications
     */
    public function setCharacter(\CorahnRin\CharactersBundle\Entity\Characters $character = null)
    {
        $this->character = $character;
    
        return $this;
    }

    /**
     * Get character
     *
     * @return \CorahnRin\CharactersBundle\Entity\Characters 
     */
    public function getCharacter()
    {
        return $this->character;
    }

    /**
     * Set user
     *
     * @param \CorahnRin\UsersBundle\Entity\Users $user
     * @return CharModifications
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