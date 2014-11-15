<?php

namespace EsterenMaps\AdminBundle\Controller;

use EsterenMaps\MapsBundle\Entity\Factions;
use EsterenMaps\MapsBundle\Entity\Maps;
use EsterenMaps\MapsBundle\Entity\Markers;
use EsterenMaps\MapsBundle\Entity\MarkersTypes;
use EsterenMaps\MapsBundle\Entity\Routes;
use EsterenMaps\MapsBundle\Entity\RoutesTypes;
use EsterenMaps\MapsBundle\Entity\Zones;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminMapsController extends Controller {

    /** @var MarkersTypes[] */
    private $markersTypes;

    /** @var RoutesTypes[] */
    private $routesTypes;

    /** @var Factions[] */
    private $factions;

    /**
     * @Route("/admin/esterenmaps/maps/maps/edit-interactive/{id}", name="admin_esterenmaps_maps_maps_editInteractive")
     * @Template()
     * @param Maps $map
     * @param Request $request
     * @return array
     */
    public function editAction(Maps $map, Request $request) {

        if (!$this->container->get('security.context')->isGranted('ROLE_SONATA_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $routesTypes = $em->getRepository('EsterenMapsBundle:RoutesTypes')->findAll(true);
        $markersTypes = $em->getRepository('EsterenMapsBundle:MarkersTypes')->findAll(true);
        $factions = $em->getRepository('EsterenMapsBundle:Factions')->findAll(true);

        $this->routesTypes = $routesTypes;
        $this->markersTypes = $markersTypes;
        $this->factions = $factions;

        if ($request->getMethod() == 'POST') {

            $this->updateMarkers($map, $request);
            $this->updateZones($map, $request);
            $this->updateRoutes($map, $request);

            $map->refresh();

            $em->persist($map);

            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Modifications enregistrées !');
            return $this->redirect($this->generateUrl('admin_esterenmaps_maps_maps_editInteractive',array('id'=>$map->getId())));

        }

//        $route_init = $this->generateUrl('esterenmaps_maps_api_init');
        $route_init = '';

        $maxZones = $em->getRepository('EsterenMapsBundle:Zones')->getMax();
        $idsZones = $em->getRepository('EsterenMapsBundle:Zones')->getIds();

        $maxRoutes = $em->getRepository('EsterenMapsBundle:Routes')->getMax();
        $idsRoutes = $em->getRepository('EsterenMapsBundle:Routes')->getIds();

        $maxMarkers = $em->getRepository('EsterenMapsBundle:Markers')->getMax();
        $idsMarkers = $em->getRepository('EsterenMapsBundle:Markers')->getIds();

        $tilesUrl = $this->generateUrl('esterenmaps_api_tiles_tile_distant', array('id'=>0,'x'=>0,'y'=>0,'zoom'=>0), true);
        $tilesUrl = str_replace('0/0/0/0','{id}/{z}/{x}/{y}', $tilesUrl);
        $tilesUrl = preg_replace('~app_dev(_fast)\.php/~isUu', '', $tilesUrl);

        return array(
            'admin_pool' => $this->get('sonata.admin.pool'),// Config sonata
            'map' => $map,
            'tilesUrl' => $tilesUrl,
            'tile_size' => $this->container->getParameter('esterenmaps.tile_size'),
            'routesTypes' => $routesTypes,
            'markersTypes' => $markersTypes,
            'factions' => $factions,
            'idsMarkers' => ++$idsMarkers,
            'idsZones' => ++$idsZones,
            'idsRoutes' => ++$idsRoutes,
            'emptyMarker' => new Markers(),
            'route_init' => $route_init,
            'maxZones' => $maxZones,
            'maxRoutes' => $maxRoutes,
            'maxMarkers' => $maxMarkers,
        );
    }


    private function updateZones(Maps &$map, Request $request) {
        $post = $request->request;

        $polygons = $post->get('polygon');

        $em = $this->getDoctrine()->getManager();

        $zones_map = array();
        foreach ($map->getZones() as $zone) {
            $zones_map[$zone->getId()] = $zone;
            $em->persist($zone);
        }

        if (!empty($polygon)) {
            foreach ($polygons as $id => $polygon) {
                if (isset($zones_map[$id])) {
                    $zone = $zones_map[$id];
                } else {
                    $zone = new Zones();
                }

                // Définition de la zone
                $zone->setName($polygon['name'])
                    ->setMap($map)
                    ->setFaction($this->factions[$polygon['faction']])
                    ->setCoordinates($polygon['coordinates']);
                unset($zones_map[$id]);

                $em->persist($zone);

            }
        }

        // Suppression des zones absentes des données POST
        foreach ($zones_map as $zone) {
            $map->removeZone($zone);
            $em->remove($zone);
        }

    }

    private function updateMarkers(Maps &$map, Request $request) {
        $post = $request->request;

        $markers_post = $post->get('marker');
        $t = $map->getMarkers();
        /** @var Markers[] $markers_map */
        $markers_map = array();
        foreach ($t as $m) { $markers_map[$m->getId()] = $m; }

        $em = $this->getDoctrine()->getManager();

        if (!$markers_post) { $markers_post = array(); }

        // Mise à jour des marqueurs
        foreach ($markers_post as $id => $marker_post) {
            if (isset($markers_map[$id])) {
                $marker = $markers_map[$id];
            } else {
                $marker = new Markers();
            }
            $marker
                ->setName($marker_post['name'])
                ->setAltitude($marker_post['altitude'])
                ->setLatitude($marker_post['latitude'])
                ->setLongitude($marker_post['longitude'])
                ->setMarkerType($this->markersTypes[$marker_post['type']])
                ->setMap($map)
            ;
            if ($marker_post['faction']) {
                $marker->setFaction($this->factions[$marker_post['faction']]);
                $em->persist($this->factions[$marker_post['faction']]);
            } else {
                $marker->setFaction(null);
            }
            $em->persist($marker);
        }

        // Suppression des marqueurs absents des données POST
        foreach ($markers_map as $marker) {
            $id = $marker->getId();
            if (!isset($markers_post[$id])) {
                $map->removeMarker($marker);
                $em->remove($marker);
            }
        }

    }

    private function updateRoutes(Maps &$map, Request $request) {
        $post = $request->request;

        $polylines = $post->get('polyline');

        $em = $this->getDoctrine()->getManager();

        $routes_map = array();
        foreach ($map->getRoutes() as $route) {
            $routes_map[$route->getId()] = $route;
            $em->persist($route);
        }

        $markers_ids = array();
        foreach ($map->getMarkers() as $marker) {
            $markers_ids[$marker->getId()] = $marker;
            $em->persist($marker);
        }

        if (!empty($polylines)) {
            foreach ($polylines as $id => $polyline) {
                if (isset($routes_map[$id])) {
                    $route = $routes_map[$id];
                } else {
                    $route = new Routes();
                }

                // Définition de la route
                $route->setName($polyline['name'])
                    ->setMap($map)
                    ->setRouteType($this->routesTypes[$polyline['type']])
                    ->setFaction($polyline['faction'] ? $this->factions[$polyline['faction']] : null)
                    ->setMarkerStart($markers_ids[$polyline['markerStart']])
                    ->setMarkerEnd(isset($markers_ids[$polyline['markerEnd']]) ? $markers_ids[$polyline['markerEnd']] : null)
                    ->setCoordinates($polyline['coordinates']);
                unset($routes_map[$id]);

                $route->refresh();

                $em->persist($route);

            }
        }

        // Suppression des routes absentes des données POST
        foreach ($routes_map as $route) {
            $map->removeRoute($route);
            $em->remove($route);
        }

    }
}