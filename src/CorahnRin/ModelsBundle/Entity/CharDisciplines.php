<?php

namespace CorahnRin\ModelsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CharDisciplines
 *
 * @ORM\Table(name="characters_disciplines")
 * @ORM\Entity(repositoryClass="CorahnRin\ModelsBundle\Repository\CharDisciplinesRepository")
 */
class CharDisciplines {

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Characters", inversedBy="disciplines")
     * @Assert\NotNull()
     */
    protected $character;

    /**
     * @var Disciplines
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Disciplines")
     * @Assert\NotNull()
     */
    protected $discipline;

    /**
     * @var Domains
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Domains")
     * @Assert\NotNull()
     */
    protected $domain;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @Assert\NotNull()
     * @Assert\GreaterThanOrEqual(value=0)
     */
    protected $score;

    /**
     * Set score
     *
     * @param integer $score
     * @return CharDisciplines
     */
    public function setScore($score) {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore() {
        return $this->score;
    }

    /**
     * Set character
     *
     * @param Characters $character
     * @return CharDisciplines
     */
    public function setCharacter(Characters $character) {
        $this->character = $character;

        return $this;
    }

    /**
     * Get character
     *
     * @return Characters
     */
    public function getCharacter() {
        return $this->character;
    }

    /**
     * Set discipline
     *
     * @param Disciplines $discipline
     * @return CharDisciplines
     */
    public function setDiscipline(Disciplines $discipline) {
        $this->discipline = $discipline;

        return $this;
    }

    /**
     * Get discipline
     *
     * @return Disciplines
     */
    public function getDiscipline() {
        return $this->discipline;
    }

    /**
     * Set domain
     *
     * @param Domains $domain
     * @return CharDisciplines
     */
    public function setDomain(Domains $domain) {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return Domains
     */
    public function getDomain() {
        return $this->domain;
    }
}