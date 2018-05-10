<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\Api;

use Doctrine\Common\Persistence\ObjectManager;
use EsterenMaps\Entity\Factions;
use EsterenMaps\Entity\Maps;
use EsterenMaps\Entity\Markers;
use EsterenMaps\Entity\MarkersTypes;
use EsterenMaps\Entity\Routes;
use EsterenMaps\Entity\RoutesTypes;
use EsterenMaps\Entity\TransportTypes;
use EsterenMaps\Entity\Zones;
use EsterenMaps\Entity\ZonesTypes;
use EsterenMaps\Form\ApiMarkersType;
use EsterenMaps\Form\ApiRouteType;
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
        $data['map'] = $this->em->getRepository(Maps::class)->findForApi($id);
        $data['map']['markers'] = $this->em->getRepository(Markers::class)->findForApiByMap($id);
        $data['map']['routes'] = $this->em->getRepository(Routes::class)->findForApiByMap($id);
        $data['map']['zones'] = $this->em->getRepository(Zones::class)->findForApiByMap($id);

        // References
        $data['references']['markers_types'] = $this->em->getRepository(MarkersTypes::class)->findForApi();
        $data['references']['routes_types'] = $this->em->getRepository(RoutesTypes::class)->findForApi();
        $data['references']['zones_types'] = $this->em->getRepository(ZonesTypes::class)->findForApi();
        $data['references']['factions'] = $this->em->getRepository(Factions::class)->findForApi();
        $data['references']['transports'] = $this->em->getRepository(TransportTypes::class)->findForApi();

        // Pre-compiled templates
        if ($editMode === true) {
            $data['templates'] = $this->getEditModeTemplates($data);
        } else {
            $data['templates'] = $this->getTemplates($data);
        }

        return $this->filterMapData($data);
    }

    private function filterMapData(array $data): array
    {
        $data['map']['bounds'] = json_decode($data['map']['bounds'], true);

        foreach ($data['map']['markers'] as &$marker) {
            $marker['latitude'] = (float) $marker['latitude'];
            $marker['longitude'] = (float) $marker['longitude'];
        }

        foreach ($data['map']['zones'] as &$zone) {
            $zone['coordinates'] = $this->filterCoordinates(json_decode($zone['coordinates'], true));
        }

        foreach ($data['map']['routes'] as &$route) {
            $route['coordinates'] = $this->filterCoordinates(json_decode($route['coordinates'], true));
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

    private function filterCoordinates($coordinates): array
    {
        foreach ($coordinates as &$coordinate) {
            $coordinate['lat'] = (float) $coordinate['lat'];
            $coordinate['lng'] = (float) $coordinate['lng'];
        }

        return $coordinates;
    }

    private function getTemplates(array $data)
    {
        return [
            'LeafletPopupMarkerBaseContent' => $this->twig->render('esteren_maps/Api/popupContentMarker.html.twig', [
                'markersTypes' => $data['references']['markers_types'],
                'factions'     => $data['references']['factions'],
            ]),
            'LeafletPopupPolylineBaseContent' => $this->twig->render('esteren_maps/Api/popupContentPolyline.html.twig', [
                'markers'     => $data['map']['markers'],
                'routesTypes' => $data['references']['routes_types'],
                'factions'    => $data['references']['factions'],
            ]),
            'LeafletPopupPolygonBaseContent' => $this->twig->render('esteren_maps/Api/popupContentPolygon.html.twig', [
                'zonesTypes' => $data['references']['zones_types'],
                'factions'   => $data['references']['factions'],
            ]),
        ];
    }

    private function getEditModeTemplates(array $data)
    {
        return [
            'LeafletPopupMarkerBaseContent' => $this->twig->render('esteren_maps/Api/popupContentMarkerEditMode.html.twig', [
                'form' => $this->formFactory->create(ApiMarkersType::class)->createView(),
            ]),
            'LeafletPopupPolylineBaseContent' => $this->twig->render('esteren_maps/Api/popupContentPolylineEditMode.html.twig', [
                'form' => $this->formFactory->create(ApiRouteType::class)->createView(),
            ]),
            'LeafletPopupPolygonBaseContent' => $this->twig->render('esteren_maps/Api/popupContentPolygonEditMode.html.twig', [
                'zonesTypes' => $data['references']['zones_types'],
                'factions'   => $data['references']['factions'],
                'display'    => true,
            ]),
        ];
    }
}
