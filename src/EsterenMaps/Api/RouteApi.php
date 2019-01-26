<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\Api;

use EsterenMaps\Repository\FactionsRepository;
use EsterenMaps\Repository\MapsRepository;
use EsterenMaps\Repository\MarkersRepository;
use EsterenMaps\Repository\RoutesTypesRepository;

class RouteApi
{
    private $routesTypesRepository;
    private $mapsRepository;
    private $markersRepository;
    private $factionsRepository;

    public function __construct(
        RoutesTypesRepository $routesTypesRepository,
        MapsRepository $mapsRepository,
        MarkersRepository $markersRepository,
        FactionsRepository $factionsRepository
    ) {
        $this->routesTypesRepository = $routesTypesRepository;
        $this->mapsRepository = $mapsRepository;
        $this->markersRepository = $markersRepository;
        $this->factionsRepository = $factionsRepository;
    }

    public function sanitizeRequestData(array $data): array
    {
        if (isset($data['map'])) {
            $data['map'] = $this->mapsRepository->find($data['map']);
        }

        if (isset($data['faction'])) {
            $data['faction'] = $this->factionsRepository->find($data['faction']);
        }

        if (isset($data['routeType'])) {
            $data['routeType'] = $this->routesTypesRepository->find($data['routeType']);
        }

        if (isset($data['markerStart'])) {
            $data['markerStart'] = $this->markersRepository->find($data['markerStart']);
        }

        if (isset($data['markerEnd'])) {
            $data['markerEnd'] = $this->markersRepository->find($data['markerEnd']);
        }

        return $data;
    }
}
