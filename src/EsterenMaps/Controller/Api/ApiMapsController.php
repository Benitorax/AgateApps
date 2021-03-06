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

namespace EsterenMaps\Controller\Api;

use EsterenMaps\Api\MapApi;
use Main\DependencyInjection\PublicService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ApiMapsController implements PublicService
{
    private $api;
    private $security;

    public function __construct(
        MapApi $api,
        AuthorizationCheckerInterface $security
    ) {
        $this->api = $api;
        $this->security = $security;
    }

    /**
     * @Route(
     *     "/api/maps/{id}",
     *     name="maps_api_maps_get",
     *     requirements={"id" = "\d+"},
     *     methods={"GET"}
     * )
     */
    public function getMap(int $id, Request $request): JsonResponse
    {
        if (!$this->security->isGranted(['ROLE_MAPS_VIEW', 'SUBSCRIBED_TO_MAPS_VIEW', 'ROLE_ADMIN'])) {
            throw new AccessDeniedException();
        }

        $response = new JsonResponse();

        // Fixes issues with floats converted to string when array is encoded.
        $response->setEncodingOptions($response::DEFAULT_ENCODING_OPTIONS | JSON_PRESERVE_ZERO_FRACTION);

        $cache = [];

        $editMode = $request->query->has('edit_mode');

        if ($editMode && !$this->security->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        if (!$editMode) {
            $cache = [
                'max_age' => 600,
                's_maxage' => 3600,
                'public' => false,
            ];
        }

        return $response
            ->setData($this->api->getMap($id, $editMode))
            ->setCache($cache)
        ;
    }

    /**
     * @Route(
     *     "/api/maps/corahn_rin/{id}",
     *     name="api_maps_corahn_rin",
     *     requirements={"id" = "\d+"},
     *     methods={"GET"}
     * )
     */
    public function getCorahnRinMap(int $id): JsonResponse
    {
        $response = new JsonResponse();

        // Fixes issues with floats converted to string when array is encoded.
        $response->setEncodingOptions($response::DEFAULT_ENCODING_OPTIONS | JSON_PRESERVE_ZERO_FRACTION);

        $mapData = $this->api->getMap($id);

        $mapData['map']['routes'] = [];
        $mapData['map']['markers'] = [];
        $mapData['references']['markers_types'] = [];
        $mapData['references']['routes_types'] = [];
        $mapData['references']['transports'] = [];

        return $response
            ->setData($mapData)
            ->setCache([
                'max_age' => 600,
                's_maxage' => 3600,
                'public' => true,
            ])
        ;
    }
}
