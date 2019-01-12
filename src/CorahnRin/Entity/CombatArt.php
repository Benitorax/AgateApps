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
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="combat_arts")
 * @ORM\Entity(repositoryClass="CorahnRin\Repository\CombatArtsRepository")
 */
class CombatArt
{
    use HasBook;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="ranged", type="boolean")
     */
    private $ranged;

    /**
     * @var bool
     *
     * @ORM\Column(name="melee", type="boolean")
     */
    private $melee;

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
     * @return CombatArt
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
     * Set description.
     *
     * @param string $description
     *
     * @return CombatArt
     *
     * @codeCoverageIgnore
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
     *
     * @codeCoverageIgnore
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set ranged.
     *
     * @param bool $ranged
     *
     * @return CombatArt
     *
     * @codeCoverageIgnore
     */
    public function setRanged($ranged)
    {
        $this->ranged = $ranged;

        return $this;
    }

    /**
     * Get ranged.
     *
     * @return bool
     *
     * @codeCoverageIgnore
     */
    public function getRanged()
    {
        return $this->ranged;
    }

    /**
     * Set melee.
     *
     * @param bool $melee
     *
     * @return CombatArt
     *
     * @codeCoverageIgnore
     */
    public function setMelee($melee)
    {
        $this->melee = $melee;

        return $this;
    }

    /**
     * Get melee.
     *
     * @return bool
     *
     * @codeCoverageIgnore
     */
    public function getMelee()
    {
        return $this->melee;
    }
}
