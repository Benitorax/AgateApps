<?php

declare(strict_types=1);

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Entity;

use CorahnRin\Entity\Traits\HasBook;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="disorders")
 * @ORM\Entity(repositoryClass="CorahnRin\Repository\MentalDisorderRepository")
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
     * @var MentalDisorderWay[]|Collection
     *
     * @ORM\OneToMany(targetEntity="CorahnRin\Entity\MentalDisorderWay", mappedBy="disorder")
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

    public function addWay(MentalDisorderWay $ways): self
    {
        $this->ways[] = $ways;

        return $this;
    }

    public function removeWay(MentalDisorderWay $ways): self
    {
        $this->ways->removeElement($ways);

        return $this;
    }

    /**
     * @return MentalDisorderWay[]|ArrayCollection
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
