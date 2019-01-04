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
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * RoutesTypes.
 *
 * @ORM\Table(name="maps_routes_types")
 * @ORM\Entity(repositoryClass="EsterenMaps\Repository\RoutesTypesRepository")
 */
class RoutesTypes implements EntityToClearInterface
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
     * @var Resources[]
     * @ORM\ManyToMany(targetEntity="Resources", mappedBy="routesTypes")
     */
    protected $resources;

    /**
     * @var Route[]
     * @ORM\OneToMany(targetEntity="Route", mappedBy="routeType")
     */
    protected $routes;

    /**
     * @var TransportModifiers[]
     * @ORM\OneToMany(targetEntity="EsterenMaps\Entity\TransportModifiers", mappedBy="routeType")
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
        $this->resources = new ArrayCollection();
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
     * @return RoutesTypes
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
     * Add resources.
     *
     *
     * @return RoutesTypes
     */
    public function addResource(Resources $resources)
    {
        $this->resources[] = $resources;

        return $this;
    }

    /**
     * Remove resources.
     */
    public function removeResource(Resources $resources)
    {
        $this->resources->removeElement($resources);
    }

    /**
     * Get resources.
     *
     * @return Resources[]
     *
     * @codeCoverageIgnore
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * Add routes.
     *
     *
     * @return RoutesTypes
     */
    public function addRoute(Route $routes)
    {
        $this->routes[] = $routes;

        return $this;
    }

    /**
     * Remove routes.
     */
    public function removeRoute(Route $routes)
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
     * @return RoutesTypes
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
     * @return RoutesTypes
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
     * @return RoutesTypes
     */
    public function addTransport(TransportModifiers $transports)
    {
        $this->transports[] = $transports;

        return $this;
    }

    /**
     * Remove transports.
     *
     *
     * @return RoutesTypes
     */
    public function removeTransport(TransportModifiers $transports)
    {
        $this->transports->removeElement($transports);

        return $this;
    }

    /**
     * Get transports.
     *
     * @return TransportModifiers[]
     *
     * @codeCoverageIgnore
     */
    public function getTransports()
    {
        return $this->transports;
    }

    /**
     * @return TransportModifiers
     */
    public function getTransport(TransportTypes $transportType)
    {
        $transports = $this->transports->filter(function (TransportModifiers $element) use ($transportType) {
            return $element->getTransportType()->getId() === $transportType->getId();
        });

        if (!$transports->count()) {
            throw new \InvalidArgumentException('RouteType object should have all types of transports bound to it. Could not find: "'.$transportType.'".');
        }

        return $transports->first();
    }
}
