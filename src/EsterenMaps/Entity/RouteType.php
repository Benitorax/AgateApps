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

namespace EsterenMaps\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use EsterenMaps\Cache\EntityToClearInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Table(name="maps_routes_types")
 * @ORM\Entity(repositoryClass="EsterenMaps\Repository\RoutesTypesRepository")
 */
class RouteType implements EntityToClearInterface
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
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=75, nullable=true)
     */
    protected $color;

    /**
     * @var Route[]|Collection
     * @ORM\OneToMany(targetEntity="EsterenMaps\Entity\Route", mappedBy="routeType")
     */
    protected $routes;

    /**
     * @var TransportModifier[]|Collection
     * @ORM\OneToMany(targetEntity="EsterenMaps\Entity\TransportModifier", mappedBy="routeType")
     */
    protected $transports;

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->routes = new ArrayCollection();
        $this->transports = new ArrayCollection();
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
     * @return RouteType
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
     * Add routes.
     *
     *
     * @return RouteType
     */
    public function addRoute(Route $routes)
    {
        $this->routes[] = $routes;

        return $this;
    }

    /**
     * Remove routes.
     */
    public function removeRoute(Route $routes): void
    {
        $this->routes->removeElement($routes);
    }

    /**
     * Get routes.
     *
     * @return Route[]
     *
     * @codeCoverageIgnore
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     *
     * @return RouteType
     *
     * @codeCoverageIgnore
     */
    public function setColor($color)
    {
        $this->color = $color;

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
     * @return RouteType
     *
     * @codeCoverageIgnore
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Add transports.
     *
     *
     * @return RouteType
     */
    public function addTransport(TransportModifier $transports)
    {
        $this->transports[] = $transports;

        return $this;
    }

    /**
     * Remove transports.
     *
     *
     * @return RouteType
     */
    public function removeTransport(TransportModifier $transports)
    {
        $this->transports->removeElement($transports);

        return $this;
    }

    /**
     * Get transports.
     *
     * @return TransportModifier[]
     *
     * @codeCoverageIgnore
     */
    public function getTransports()
    {
        return $this->transports;
    }

    /**
     * @return TransportModifier
     */
    public function getTransport(TransportType $transportType)
    {
        $transports = $this->transports->filter(function (TransportModifier $element) use ($transportType) {
            return $element->getTransportType()->getId() === $transportType->getId();
        });

        if (!$transports->count()) {
            throw new \InvalidArgumentException('RouteType object should have all types of transports bound to it. Could not find: "'.$transportType.'".');
        }

        return $transports->first();
    }
}
