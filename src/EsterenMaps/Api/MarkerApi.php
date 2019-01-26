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
use EsterenMaps\Repository\MarkersTypesRepository;

class MarkerApi
{
    private $markersTypesRepository;
    private $mapsRepository;
    private $factionsRepository;

    public function __construct(
        MarkersTypesRepository $markersTypesRepository,
        MapsRepository $mapsRepository,
        FactionsRepository $factionsRepository
    ) {
        $this->markersTypesRepository = $markersTypesRepository;
        $this->mapsRepository = $mapsRepository;
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

        if (isset($data['markerType'])) {
            $data['markerType'] = $this->markersTypesRepository->find($data['markerType']);
        }

        if (isset($data['altitude'])) {
            $data['altitude'] = (float) $data['altitude'];
        }

        if (isset($data['latitude'])) {
            $data['latitude'] = (float) $data['latitude'];
        }

        if (isset($data['longitude'])) {
            $data['longitude'] = (float) $data['longitude'];
        }

        return $data;
    }
}
