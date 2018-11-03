<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\EsterenMaps\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\WebTestCase as PiersTestCase;

class ApiMapsControllerTest extends WebTestCase
{
    use PiersTestCase;

    public function testMapInfo()
    {
        $data = $this->getMapData();

        static::assertSame(1, $data['map']['id'] ?? null);
        static::assertSame('tri-kazel', $data['map']['name_slug'] ?? null);
        static::assertInternalType('array', $data['map']['bounds'] ?? null);
        $mapKeys = [
            'id', 'name', 'name_slug', 'image', 'description', 'max_zoom', 'start_zoom', 'start_x', 'start_y',
            'bounds', 'coordinates_ratio', 'markers', 'routes', 'zones',
        ];
        $dataKeys = \array_keys($data['map']);
        static::assertSame(\sort($mapKeys), \sort($dataKeys));
    }

    public function testMapMarkers()
    {
        $data = $this->getMapData();

        $marker = $data['map']['markers'][8] ?? null;
        static::assertSame('Osta-Baille', $marker['name'] ?? null);
        static::assertInternalType('float', $marker['latitude'] ?? null);
        static::assertInternalType('float', $marker['longitude'] ?? null);
        static::assertInternalType('int', $marker['marker_type'] ?? null);
        static::assertInternalType('int', $marker['faction'] ?? null);
        $markerKeys = ['id', 'name', 'description', 'latitude', 'longitude', 'marker_type', 'faction'];
        $dataKeys = \array_keys($marker ?: []);
        static::assertSame(\sort($markerKeys), \sort($dataKeys));
    }

    public function testMapRoutes()
    {
        $data = $this->getMapData();

        // Route
        $route = $data['map']['routes'][700] ?? null;
        static::assertNotNull($route);
        static::assertSame('From 0,0 to 0,10', $route['name']);
        $routeKeys = [
            'id', 'name', 'description', 'coordinates', 'distance', 'guarded',
            'marker_start', 'marker_end', 'faction', 'route_type',
        ];
        $dataKeys = \array_keys($route ?: []);
        static::assertSame(\sort($routeKeys), \sort($dataKeys));
        static::assertInternalType('array', $route['coordinates'] ?? null);
        static::assertArrayHasKey('lat', $route['coordinates'][0] ?? null);
        static::assertArrayHasKey('lng', $route['coordinates'][0] ?? null);
        static::assertInternalType('float', $route['coordinates'][0]['lat'] ?? null);
        static::assertInternalType('float', $route['coordinates'][0]['lng'] ?? null);
        static::assertInternalType('float', $route['distance'] ?? null);
        static::assertInternalType('int', $route['route_type'] ?? null);
        static::assertInternalType('int', $route['marker_start'] ?? null);
        static::assertInternalType('int', $route['marker_end'] ?? null);
        static::assertNull($route['faction']);
    }

    public function testMapZones()
    {
        $data = $this->getMapData();

        $zone = $data['map']['zones'][1] ?? null;
        static::assertNotNull($zone);
        static::assertSame('Kingdom test', $zone['name']);
        $zoneKeys = ['id', 'name', 'description', 'coordinates', 'faction', 'zone_type'];
        $dataKeys = \array_keys($zone ?: []);
        static::assertSame(\sort($zoneKeys), \sort($dataKeys));
        static::assertInternalType('array', $zone['coordinates'] ?? null);
        static::assertArrayHasKey('lat', $zone['coordinates'][0] ?? null);
        static::assertArrayHasKey('lng', $zone['coordinates'][0] ?? null);
        static::assertInternalType('float', $zone['coordinates'][0]['lat'] ?? null);
        static::assertInternalType('float', $zone['coordinates'][0]['lng'] ?? null);
        static::assertInternalType('int', $zone['zone_type'] ?? null);
        static::assertInternalType('int', $zone['faction'] ?? null);
    }

    public function testMapTemplates()
    {
        $data = $this->getMapData();

        static::assertContains('id="marker_popup_name"', $data['templates']['LeafletPopupMarkerBaseContent'] ?? null);
        static::assertContains('id="polyline_popup_name"', $data['templates']['LeafletPopupPolylineBaseContent'] ?? null);
        static::assertContains('id="polygon_popup_name"', $data['templates']['LeafletPopupPolygonBaseContent'] ?? null);
    }

    public function testMapMarkersTypes()
    {
        $data = $this->getMapData();

        $type = $data['references']['markers_types'][1] ?? null;
        static::assertSame('City', $type['name'] ?? null);
        $typeKeys = ['id', 'name', 'description', 'icon', 'icon_width', 'icon_height', 'icon_center_x', 'icon_center_y'];
        $dataKeys = \array_keys($type ?: []);
        static::assertSame(\sort($typeKeys), \sort($dataKeys));
        static::assertInternalType('int', $type['icon_width'] ?? null);
        static::assertInternalType('int', $type['icon_height'] ?? null);
    }

    public function testMapRoutesTypes()
    {
        $data = $this->getMapData();

        $type = $data['references']['routes_types'][1] ?? null;
        static::assertSame('Track', $type['name'] ?? null);
        $typeKeys = ['id', 'name', 'description', 'color'];
        $dataKeys = \array_keys($type ?: []);
        static::assertSame(\sort($typeKeys), \sort($dataKeys));
        static::assertInternalType('string', $type['color'] ?? null);
    }

    public function testMapZonesTypes()
    {
        $data = $this->getMapData();

        $type = $data['references']['zones_types'][2] ?? null;
        static::assertSame('Kingdom', $type['name'] ?? null);
        $typeKeys = ['id', 'name', 'description', 'color', 'parent_id'];
        $dataKeys = \array_keys($type ?: []);
        static::assertSame(\sort($typeKeys), \sort($dataKeys));
        static::assertInternalType('string', $type['color'] ?? null);
        static::assertInternalType('int', $type['parent_id'] ?? null);
    }

    public function testMapFactions()
    {
        $data = $this->getMapData();

        $type = $data['references']['factions'][1] ?? null;
        static::assertSame('Faction Test', $type['name'] ?? null);
        $typeKeys = ['id', 'name', 'description'];
        $dataKeys = \array_keys($type ?: []);
        static::assertSame(\sort($typeKeys), \sort($dataKeys));
    }

    private function getMapData()
    {
        $client = $this->getClient('maps.esteren.docker');

        static::setToken($client, 'map_allowed', ['ROLE_MAPS_VIEW']);

        $client->request('GET', '/fr/api/maps/1');

        $response = $client->getResponse();
        static::assertSame(200, $response->getStatusCode());
        $jsonContent = $response->getContent();
        $data = \json_decode($jsonContent, true);

        if (\json_last_error()) {
            static::fail(\json_last_error_msg());
        }

        return $data;
    }
}
