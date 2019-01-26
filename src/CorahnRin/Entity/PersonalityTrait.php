<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Entity;

use CorahnRin\Data\Ways;
use CorahnRin\Entity\Traits\HasBook;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CorahnRin\Repository\TraitsRepository")
 * @ORM\Table(name="traits")
 */
class PersonalityTrait
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
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    protected $nameFemale;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $isQuality;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_major", type="boolean")
     */
    protected $major;

    /**
     * @var string
     *
     * @ORM\Column(name="way", type="string")
     */
    protected $way;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return (string) $this->name;
    }

    public function setNameFemale(string $nameFemale): self
    {
        $this->nameFemale = $nameFemale;

        return $this;
    }

    public function getNameFemale(): string
    {
        return (string) $this->nameFemale;
    }

    public function setQuality(bool $isQuality): self
    {
        $this->isQuality = $isQuality;

        return $this;
    }

    public function isQuality()
    {
        return (bool) $this->isQuality;
    }

    public function setMajor(bool $major): self
    {
        $this->major = $major;

        return $this;
    }

    public function isMajor()
    {
        return (bool) $this->major;
    }

    public function setWay(string $way): self
    {
        Ways::validateWay($way);

        $this->way = $way;

        return $this;
    }

    public function getWay(): string
    {
        return (string) $this->way;
    }
}
