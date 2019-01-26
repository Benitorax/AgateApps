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

namespace EsterenMaps\Controller;

use EsterenMaps\Entity\Map;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route(host="%esteren_domains.esterenmaps%")
 */
class MapsController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="esterenmaps_maps_list")
     */
    public function indexAction(): Response
    {
        /** @var Map[] $allMaps */
        $allMaps = $this->getDoctrine()->getRepository(Map::class)->findAllRoot();

        return $this->render('esteren_maps/Maps/index.html.twig', [
            'list' => $allMaps,
        ]);
    }

    /**
     * @Route("/map-{nameSlug}", methods={"GET"}, name="esterenmaps_maps_maps_view")
     */
    public function viewAction(Map $map, Request $request): Response
    {
        if (!$this->getUser() || !$this->isGranted(['ROLE_MAPS_VIEW', 'SUBSCRIBED_TO_MAPS_VIEW', 'ROLE_ADMIN'])) {
            throw $this->createAccessDeniedException('Access denied.');
        }

        $response = new Response();
        $response->setCache([
            'max_age' => 600,
            's_maxage' => 3600,
            'public' => false,
        ]);

        $tilesUrl = $this->generateUrl(
            'esterenmaps_api_tiles',
            ['id' => 0, 'x' => 0, 'y' => 0, 'zoom' => 0, 'host' => $request->getHost()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $tilesUrl = \str_replace('0/0/0/0', '{id}/{z}/{x}/{y}', $tilesUrl);
        $tilesUrl = \preg_replace('~app_dev\.php/~iUu', '', $tilesUrl);

        return $this->render('esteren_maps/Maps/view.html.twig', [
            'map' => $map,
            'tilesUrl' => $tilesUrl,
            'tile_size' => $this->getParameter('esterenmaps.tile_size'),
        ], $response);
    }
}
