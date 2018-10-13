<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\Controller;

use EsterenMaps\Entity\Maps;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(host="%esteren_domains.esterenmaps%")
 */
class MapsController extends Controller
{
    /**
     * @Route("/", methods={"GET"}, name="esterenmaps_maps_list")
     */
    public function indexAction(): Response
    {
        /** @var Maps[] $allMaps */
        $allMaps = $this->getDoctrine()->getRepository(Maps::class)->findAllRoot();

        $updatedAt = null;

        foreach ($allMaps as $map) {
            if (!$updatedAt) {
                $updatedAt = $map->getUpdatedAt();
                continue;
            }
            if ($updatedAt < $map->getUpdatedAt()) {
                $updatedAt = $map->getUpdatedAt();
            }
        }

        return $this->render('esteren_maps/Maps/index.html.twig', [
            'list' => $allMaps,
        ]);
    }

    /**
     * @Route("/map-{nameSlug}", methods={"GET"}, name="esterenmaps_maps_maps_view")
     */
    public function viewAction(Maps $map): Response
    {
        if (!$this->isGranted(['ROLE_USER', 'ROLE_MAPS_VIEW']) || !$this->getUser()) {
            throw $this->createAccessDeniedException('Access denied.');
        }

        $response = new Response();
        $response->setCache([
            'max_age' => 600,
            's_maxage' => 3600,
            'public' => false,
        ]);

        $tilesUrl = $this->generateUrl('esterenmaps_api_tiles', ['id' => 0, 'x' => 0, 'y' => 0, 'zoom' => 0], true);
        $tilesUrl = \str_replace('0/0/0/0', '{id}/{z}/{x}/{y}', $tilesUrl);
        $tilesUrl = \preg_replace('~app_dev\.php/~iUu', '', $tilesUrl);

        return $this->render('esteren_maps/Maps/view.html.twig', [
            'map' => $map,
            'tilesUrl' => $tilesUrl,
            'tile_size' => $this->container->getParameter('esterenmaps.tile_size'),
        ], $response);
    }
}
