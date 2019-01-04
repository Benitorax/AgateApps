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

use Doctrine\ORM\Mapping as ORM;
use EsterenMaps\Cache\EntityToClearInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TransportModifiers.
 *
 * @ORM\Table(name="maps_routes_transports", uniqueConstraints={@ORM\UniqueConstraint(name="unique_route_transport", columns={"route_type_id", "transport_type_id"})})
 * @ORM\Entity
 */
class TransportModifiers implements EntityToClearInterface
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
     * @var RouteType
     *
     * @ORM\ManyToOne(targetEntity="RouteType", inversedBy="transports")
     * @ORM\JoinColumn(name="route_type_id", nullable=false)
     * @Assert\NotNull
     */
    protected $routeType;

    /**
     * @var TransportTypes
     *
     * @ORM\ManyToOne(targetEntity="EsterenMaps\Entity\TransportTypes", inversedBy="transportsModifiers")
     * @ORM\JoinColumn(name="transport_type_id", nullable=false)
     * @Assert\NotNull
     */
    protected $transportType;

    /**
     * @var float
     *
     * @ORM\Column(name="percentage", type="decimal", scale=6, precision=9, nullable=false, options={"default" = "100"})
     * @Assert\NotNull
     * @Assert\Range(max="100", min="0")
     */
    protected $percentage = 100;

    public function __toString()
    {
        return (string) $this->transportType.
            ' - '.$this->routeType.
            ' ('.$this->percentage.'%)';
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
     * @return TransportModifiers
     *
     * @codeCoverageIgnore
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return RouteType
     *
     * @codeCoverageIgnore
     */
    public function getRouteType()
    {
        return $this->routeType;
    }

    /**
     * @param RouteType $routeType
     *
     * @return TransportModifiers
     *
     * @codeCoverageIgnore
     */
    public function setRouteType($routeType)
    {
        $this->routeType = $routeType;

        return $this;
    }

    /**
     * @return TransportTypes
     *
     * @codeCoverageIgnore
     */
    public function getTransportType()
    {
        return $this->transportType;
    }

    /**
     * @return TransportModifiers
     *
     * @codeCoverageIgnore
     */
    public function setTransportType(TransportTypes $transportType)
    {
        $this->transportType = $transportType;

        return $this;
    }

    /**
     * @return float
     *
     * @codeCoverageIgnore
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * @param float $percentage
     *
     * @return TransportModifiers
     *
     * @codeCoverageIgnore
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;

        return $this;
    }
}
