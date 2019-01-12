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

use CorahnRin\Data\Ways;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="disorders_ways")
 * @ORM\Entity
 */
class MentalDisorderWay
{
    /**
     * @var MentalDisorder
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\MentalDisorder", inversedBy="ways")
     */
    protected $disorder;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="way", type="string")
     */
    protected $way;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default" = 0})
     */
    protected $major = false;

    public function __toString()
    {
        return $this->disorder->getName().' - '.$this->way;
    }

    public function setDisorder(MentalDisorder $disorder)
    {
        $this->disorder = $disorder;

        return $this;
    }

    public function getDisorder()
    {
        return $this->disorder;
    }

    public function setWay(string $way)
    {
        Ways::validateWay($way);

        $this->way = $way;

        return $this;
    }

    public function getWay(): string
    {
        return $this->way;
    }

    public function setMajor(bool $major)
    {
        $this->major = $major;

        return $this;
    }

    public function isMajor(): bool
    {
        return $this->major;
    }

    public function isMinor(): bool
    {
        return !$this->major;
    }
}
