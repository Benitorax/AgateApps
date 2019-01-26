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

namespace EsterenMaps\Api;

use EsterenMaps\Repository\FactionsRepository;
use EsterenMaps\Repository\MapsRepository;
use EsterenMaps\Repository\ZonesTypesRepository;

class ZoneApi
{
    private $zonesTypesRepository;
    private $mapsRepository;
    private $factionsRepository;

    public function __construct(
        ZonesTypesRepository $zonesTypesRepository,
        MapsRepository $mapsRepository,
        FactionsRepository $factionsRepository
    ) {
        $this->zonesTypesRepository = $zonesTypesRepository;
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

        if (isset($data['zoneType'])) {
            $data['zoneType'] = $this->zonesTypesRepository->find($data['zoneType']);
        }

        return $data;
    }
}
