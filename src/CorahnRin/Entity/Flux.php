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

use Doctrine\ORM\Mapping as ORM;

/**
 * Flux.
 *
 * @ORM\Table(name="flux")
 * @ORM\Entity
 */
class Flux
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
     * @ORM\Column(type="string", length=70, nullable=false, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    public function __toString()
    {
        return $this->name;
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
     * @return Flux
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
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return Flux
     *
     * @codeCoverageIgnore
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
