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

use CorahnRin\Entity\Traits\HasBook;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="disorders")
 * @ORM\Entity(repositoryClass="MentalDisorderRepository")
 */
class MentalDisorder
{
    use HasBook;

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
     * @ORM\Column(type="string", length=100, nullable=false, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var DisorderWay[]
     *
     * @ORM\OneToMany(targetEntity="DisorderWay", mappedBy="disorder")
     */
    protected $ways;

    public function __construct()
    {
        $this->ways = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return (string) $this->name;
    }

    public function addWay(DisorderWay $ways): self
    {
        $this->ways[] = $ways;

        return $this;
    }

    public function removeWay(DisorderWay $ways): self
    {
        $this->ways->removeElement($ways);

        return $this;
    }

    /**
     * @return DisorderWay[]|ArrayCollection
     */
    public function getWays(): iterable
    {
        return $this->ways;
    }

    public function setDescription(?string $description)
    {
        $this->description = (string) $description;

        return $this;
    }

    public function getDescription(): string
    {
        return (string) $this->description;
    }
}
