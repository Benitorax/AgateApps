<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EsterenMaps\Cache\EntityToClearInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TransportTypes.
 *
 * @ORM\Table(name="maps_transports_types")
 * @ORM\Entity(repositoryClass="EsterenMaps\Repository\TransportTypesRepository")
 */
class TransportTypes implements EntityToClearInterface, \JsonSerializable
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false, unique=true)
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255, nullable=false, unique=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="speed", type="decimal", scale=4, precision=8, nullable=false)
     * @Assert\NotNull
     * @Assert\Range(max="10000", min="-10000")
     */
    protected $speed;

    /**
     * @var ArrayCollection|TransportModifiers[]
     *
     * @ORM\OneToMany(targetEntity="EsterenMaps\Entity\TransportModifiers", mappedBy="transportType", cascade={"persist", "remove"})
     */
    protected $transportsModifiers;

    public function __construct()
    {
        $this->transportsModifiers = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
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
     * @return TransportTypes
     *
     * @codeCoverageIgnore
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return TransportTypes
     *
     * @codeCoverageIgnore
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return TransportTypes
     *
     * @codeCoverageIgnore
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return TransportTypes
     *
     * @codeCoverageIgnore
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @param string $speed
     *
     * @return TransportTypes
     *
     * @codeCoverageIgnore
     */
    public function setSpeed($speed)
    {
        $this->speed = $speed;

        return $this;
    }

    /**
     * Add transportsModifier.
     *
     * @param TransportModifiers $transportsModifier
     *
     * @return TransportTypes
     */
    public function addTransportsModifier(TransportModifiers $transportsModifier)
    {
        $this->transportsModifiers[] = $transportsModifier;

        return $this;
    }

    /**
     * Remove transportsModifier.
     *
     * @param TransportModifiers $transportsModifier
     *
     * @return $this
     */
    public function removeTransportsModifier(TransportModifiers $transportsModifier)
    {
        $this->transportsModifiers->removeElement($transportsModifier);

        return $this;
    }

    /**
     * Get transportsModifiers.
     *
     * @return ArrayCollection|TransportModifiers[]
     *
     * @codeCoverageIgnore
     */
    public function getTransportsModifiers()
    {
        return $this->transportsModifiers;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description ?: null,
            'speed' => (float) $this->speed,
        ];
    }
}
