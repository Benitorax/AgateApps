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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{
    JsonResponse, Request
};

/**
 * @Route(host="%esteren_domains.api%")
 */
class ApiMapsController extends AbstractController
{
    private $debug;
    private $api;
    private $versionCode;

    public function __construct(bool $debug, string $versionCode, MapApi $api)
    {
        $this->debug = $debug;
        $this->api = $api;
        $this->versionCode = $versionCode;
    }

    /**
     * @Route("/maps/{id}", name="esterenmaps_api_map_get", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function getAction(int $id, Request $request): JsonResponse
    {
        $response = new JsonResponse();
        $response->setLastModified($this->api->getLastUpdateTime($id));

        if ($response->isNotModified($request)) {
            return $response;
        }

        $data = $this->api->getMap($id);

        $response->setData($data);

        if (!$this->debug) {
            $response->setCache([
                'etag'          => sha1('map'.$id.$this->versionCode),
                'last_modified' => new \DateTime($this->api->getLastUpdateTime($id)),
                'max_age'       => 600,
                's_maxage'      => 600,
                'public'        => true,
            ]);
        }

        return $response;
    }
}