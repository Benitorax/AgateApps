<?php

namespace CorahnRin\CharactersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CharDomains
 *
 * @ORM\Table(name="characters_domains")
 * @ORM\Entity(repositoryClass="CorahnRin\CharactersBundle\Repository\CharDomainsRepository")
 */
class CharDomains
{
    /**
     * @var Characters
     *
     * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="\CorahnRin\CharactersBundle\Entity\Characters", inversedBy="domains")
     */
    protected $character;

    /**
     * @var Domains
     *
     * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="\CorahnRin\CharactersBundle\Entity\Domains")
     */
    protected $domain;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $score;

    /**
     * Set score
     *
     * @param integer $score
     * @return CharDomains
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set character
     *
     * @param \CorahnRin\CharactersBundle\Entity\Characters $character
     * @return CharDomains
     */
    public function setCharacter(\CorahnRin\CharactersBundle\Entity\Characters $character)
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
     * Set domain
     *
     * @param \CorahnRin\CharactersBundle\Entity\Domains $domain
     * @return CharDomains
     */
    public function setDomain(\CorahnRin\CharactersBundle\Entity\Domains $domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return \CorahnRin\CharactersBundle\Entity\Domains
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     * @return CharDomains
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
