<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\Controller\Admin\Api;

use EsterenMaps\Api\MapApi;
use Main\DependencyInjection\PublicService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ApiMapsController implements PublicService
{
    private $api;
    private $security;

    public function __construct(MapApi $api, AuthorizationCheckerInterface $security)
    {
        $this->api = $api;
        $this->security = $security;
    }

    /**
     * @Route(
     *     "/api/maps/{id}/edit-mode",
     *     name="maps_api_maps_get_editmode",
     *     requirements={"id" = "\d+"},
     *     methods={"GET"},
     *     host="%esteren_domains.backoffice%"
     * )
     */
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            // We need 404 instead of 403 to avoid dirty hacks here.
            throw new NotFoundHttpException();
        }

        return new JsonResponse($this->api->getMap($id, true));
    }
}
