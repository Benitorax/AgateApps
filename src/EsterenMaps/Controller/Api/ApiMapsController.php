<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\Controller\Api;

use EsterenMaps\Api\MapApi;
use Main\DependencyInjection\PublicService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route(host="%esteren_domains.api%")
 */
class ApiMapsController implements PublicService
{
    private $api;
    private $versionCode;
    private $security;

    public function __construct(string $versionCode, MapApi $api, AuthorizationCheckerInterface $security)
    {
        $this->api = $api;
        $this->versionCode = $versionCode;
        $this->security = $security;
    }

    /**
     * @Route("/maps/{id}", name="maps_api_maps_get", requirements={"id" = "\d+"}, methods={"GET"})
     */
    public function getAction(int $id, Request $request): JsonResponse
    {
        $response = new JsonResponse();

        // Fixes issues with floats converted to string when array is encoded.
        $response->setEncodingOptions($response::DEFAULT_ENCODING_OPTIONS | JSON_PRESERVE_ZERO_FRACTION);

        $response->setEtag($etag = \sha1('map'.$id.$this->versionCode));

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response
            ->setData($this->api->getMap($id))
            ->setCache([
                'etag' => $etag,
                'max_age' => 600,
                's_maxage' => 3600,
                'public' => true,
            ])
        ;
    }

    /**
     * @Route("/maps/{id}/edit-mode", name="maps_api_maps_get_editmode", requirements={"id" = "\d+"}, methods={"GET"})
     */
    public function getEditModeAction(int $id, Request $request): JsonResponse
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            // We need 404 instead of 403 to avoid dirty hacks here.
            throw new NotFoundHttpException();
        }

        return new JsonResponse($this->api->getMap($id, true));
    }
}
