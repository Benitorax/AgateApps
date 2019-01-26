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

use Doctrine\Common\Persistence\ObjectManager;
use EsterenMaps\Entity\Faction;
use EsterenMaps\Entity\Map;
use EsterenMaps\Entity\Marker;
use EsterenMaps\Entity\MarkerType;
use EsterenMaps\Entity\Route;
use EsterenMaps\Entity\RouteType;
use EsterenMaps\Entity\TransportType;
use EsterenMaps\Entity\Zone;
use EsterenMaps\Entity\ZoneType;
use EsterenMaps\Form\ApiMarkersType;
use EsterenMaps\Form\ApiRouteType;
use EsterenMaps\Form\ApiZoneType;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Form\FormFactoryInterface;
use Twig\Environment;

class MapApi
{
    private $em;
    private $twig;
    private $asset;
    private $formFactory;

    public function __construct(ObjectManager $em, Environment $twig, Packages $asset, FormFactoryInterface $formFactory)
    {
        $this->em = $em;
        $this->twig = $twig;
        $this->asset = $asset;
        $this->formFactory = $formFactory;
    }

    public function getMap($id, bool $editMode = false): array
    {
        return $this->doGetMap($id, $editMode);
    }

    private function doGetMap($id, bool $editMode = false): array
    {
        $data = [
            'map' => [],
            'templates' => [],
            'references' => [],
        ];

        // Map info
        $data['map'] = $this->em->getRepository(Map::class)->findForApi($id);
        $data['map']['markers'] = $this->em->getRepository(Marker::class)->findForApiByMap($id);
        $data['map']['routes'] = $this->em->getRepository(Route::class)->findForApiByMap($id);
        $data['map']['zones'] = $this->em->getRepository(Zone::class)->findForApiByMap($id);

        // References
        $data['references']['markers_types'] = $this->em->getRepository(MarkerType::class)->findForApi();
        $data['references']['routes_types'] = $this->em->getRepository(RouteType::class)->findForApi();
        $data['references']['zones_types'] = $this->em->getRepository(ZoneType::class)->findForApi();
        $data['references']['factions'] = $this->em->getRepository(Faction::class)->findForApi();
        $data['references']['transports'] = $this->em->getRepository(TransportType::class)->findForApi();

        // Pre-compiled templates
        if (true === $editMode) {
            $data['templates'] = $this->getEditModeTemplates();
        } else {
            $data['templates'] = $this->getTemplates();
        }

        return $this->filterMapData($data);
    }

    private function filterMapData(array $data): array
    {
        $data['map']['bounds'] = \json_decode($data['map']['bounds'], true);

        foreach ($data['map']['markers'] as &$marker) {
            $marker['latitude'] = (float) $marker['latitude'];
            $marker['longitude'] = (float) $marker['longitude'];
        }

        foreach ($data['map']['zones'] as &$zone) {
            $zone['coordinates'] = $this->filterCoordinates(\json_decode($zone['coordinates'], true));
        }

        foreach ($data['map']['routes'] as &$route) {
            $route['coordinates'] = $this->filterCoordinates(\json_decode($route['coordinates'], true));
            if ($route['forced_distance']) {
                $route['distance'] = $route['forced_distance'];
            }
            unset($route['forced_distance']);
        }

        foreach ($data['references']['markers_types'] as &$markerType) {
            $markerType['icon'] = $this->asset->getUrl('img/markerstypes/'.$markerType['icon']);
        }

        return $data;
    }

    private function filterCoordinates(?array $coordinates): array
    {
        if (null === $coordinates) {
            return [];
        }

        foreach ($coordinates as &$coordinate) {
            $coordinate['lat'] = (float) $coordinate['lat'];
            $coordinate['lng'] = (float) $coordinate['lng'];
        }

        return $coordinates;
    }

    private function getTemplates()
    {
        return [
            'LeafletPopupMarkerBaseContent' => $this->twig->render('esteren_maps/Api/popupContentMarker.html.twig'),
            'LeafletPopupPolylineBaseContent' => $this->twig->render('esteren_maps/Api/popupContentPolyline.html.twig'),
            'LeafletPopupPolygonBaseContent' => $this->twig->render('esteren_maps/Api/popupContentPolygon.html.twig'),
        ];
    }

    private function getEditModeTemplates()
    {
        return [
            'LeafletPopupMarkerBaseContent' => $this->twig->render('esteren_maps/Api/popupContentMarkerEditMode.html.twig', [
                'form' => $this->formFactory->create(ApiMarkersType::class)->createView(),
            ]),
            'LeafletPopupPolylineBaseContent' => $this->twig->render('esteren_maps/Api/popupContentPolylineEditMode.html.twig', [
                'form' => $this->formFactory->create(ApiRouteType::class)->createView(),
            ]),
            'LeafletPopupPolygonBaseContent' => $this->twig->render('esteren_maps/Api/popupContentPolygonEditMode.html.twig', [
                'form' => $this->formFactory->create(ApiZoneType::class)->createView(),
            ]),
        ];
    }
}
