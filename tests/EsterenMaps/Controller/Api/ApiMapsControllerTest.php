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
use Symfony\Component\DomCrawler\Crawler;
use Tests\WebTestCase as PiersTestCase;

class ApiMapsControllerTest extends WebTestCase
{
    use PiersTestCase;

    public function test getting map api without role needs authentication()
    {
        $client = $this->getClient('maps.esteren.docker');

        $client->request('GET', '/fr/api/maps/1');

        $response = $client->getResponse();
        static::assertSame(401, $response->getStatusCode());
    }

    public function test getting map api edit mode without being admin throws 403()
    {
        $client = $this->getClient('maps.esteren.docker');

        static::setToken($client, 'map-not-allowed');

        $client->request('GET', '/fr/api/maps/1?edit-mode');

        $response = $client->getResponse();
        static::assertSame(403, $response->getStatusCode());
    }

    public function test getting map api edit mode as admin data()
    {
        $client = $this->getClient('maps.esteren.docker');

        static::setToken($client, 'map-allowed', ['ROLE_ADMIN']);

        $client->request('GET', '/fr/api/maps/1?edit_mode=1');

        $response = $client->getResponse();
        static::assertSame(200, $response->getStatusCode());
        $jsonContent = $response->getContent();
        $data = \json_decode($jsonContent, true);

        if (\json_last_error()) {
            static::fail(\json_last_error_msg());
        }

        static::assertSame(1, $data['map']['id'] ?? null);
        static::assertSame('tri-kazel', $data['map']['name_slug'] ?? null);
        static::assertInternalType('array', $data['map']['bounds'] ?? null);
        $mapKeys = [
            'id', 'name', 'name_slug', 'image', 'description', 'max_zoom', 'start_zoom', 'start_x', 'start_y',
            'bounds', 'coordinates_ratio', 'markers', 'routes', 'zones',
        ];
        foreach ($mapKeys as $key) {
            static::assertArrayHasKey($key, $data['map']);
        }

        $element = new Crawler($data['templates']['LeafletPopupMarkerBaseContent']);
        static::assertCount(1, $element->filter('form[name="api_markers"]'));
        static::assertCount(1, $element->filter('#api_markers'));

        $element = new Crawler($data['templates']['LeafletPopupPolylineBaseContent']);
        static::assertCount(1, $element->filter('form[name="api_route"]'));
        static::assertCount(1, $element->filter('#api_route'));

        $element = new Crawler($data['templates']['LeafletPopupPolygonBaseContent']);
        static::assertCount(1, $element->filter('form[name="api_zone"]'));
        static::assertCount(1, $element->filter('#api_zone'));
    }

    public function provideRolesToFetchMap()
    {
        yield 'ROLE_MAPS_VIEW' => ['ROLE_MAPS_VIEW'];
        yield 'ROLE_ADMIN' => ['ROLE_ADMIN'];
    }

    /**
     * @dataProvider provideRolesToFetchMap
     */
    public function testMapInfo(string $role)
    {
        $data = $this->getMapData($role);

        static::assertSame(1, $data['map']['id'] ?? null);
        static::assertSame('tri-kazel', $data['map']['name_slug'] ?? null);
        static::assertInternalType('array', $data['map']['bounds'] ?? null);
        $mapKeys = [
            'id', 'name', 'name_slug', 'image', 'description', 'max_zoom', 'start_zoom', 'start_x', 'start_y',
            'bounds', 'coordinates_ratio', 'markers', 'routes', 'zones',
        ];
        foreach ($mapKeys as $key) {
            static::assertArrayHasKey($key, $data['map']);
        }

        $element = new Crawler($data['templates']['LeafletPopupMarkerBaseContent']);
        static::assertCount(1, $element->filter('h3#marker_popup_name'));
        static::assertCount(1, $element->filter('p#marker_popup_type'));

        $element = new Crawler($data['templates']['LeafletPopupPolylineBaseContent']);
        static::assertCount(1, $element->filter('h3#polyline_popup_name'));
        static::assertCount(1, $element->filter('p#polyline_popup_type'));

        $element = new Crawler($data['templates']['LeafletPopupPolygonBaseContent']);
        static::assertCount(1, $element->filter('h3#polygon_popup_name'));
        static::assertCount(1, $element->filter('p#polygon_popup_type'));
    }

    /**
     * @dataProvider provideRolesToFetchMap
     */
    public function testMapMarkers(string $role)
    {
        $data = $this->getMapData($role);

        $marker = $data['map']['markers'][8] ?? null;
        static::assertSame('Osta-Baille', $marker['name'] ?? null);
        static::assertInternalType('float', $marker['latitude'] ?? null);
        static::assertInternalType('float', $marker['longitude'] ?? null);
        static::assertInternalType('int', $marker['marker_type'] ?? null);
        static::assertInternalType('int', $marker['faction'] ?? null);
        $markerKeys = ['id', 'name', 'description', 'latitude', 'longitude', 'marker_type', 'faction'];
        foreach ($markerKeys as $key) {
            static::assertArrayHasKey($key, $marker);
        }
    }

    /**
     * @dataProvider provideRolesToFetchMap
     */
    public function testMapRoutes(string $role)
    {
        $data = $this->getMapData($role);

        // Route
        $route = $data['map']['routes'][700] ?? null;
        static::assertNotNull($route);
        static::assertSame('From 0,0 to 0,10', $route['name']);
        $routeKeys = [
            'id', 'name', 'description', 'coordinates', 'distance', 'guarded',
            'marker_start', 'marker_end', 'faction', 'route_type',
        ];
        foreach ($routeKeys as $key) {
            static::assertArrayHasKey($key, $route);
        }
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

    /**
     * @dataProvider provideRolesToFetchMap
     */
    public function testMapZones(string $role)
    {
        $data = $this->getMapData($role);

        $zone = $data['map']['zones'][1] ?? null;
        static::assertNotNull($zone);
        /**
         * @dataProvider provideRolesToFetchMap
         */
        static::assertSame('Kingdom test', $zone['name']);
        $zoneKeys = ['id', 'name', 'description', 'coordinates', 'faction', 'zone_type'];
        foreach ($zoneKeys as $key) {
            static::assertArrayHasKey($key, $zone);
        }
        static::assertInternalType('array', $zone['coordinates'] ?? null);
        static::assertArrayHasKey('lat', $zone['coordinates'][0] ?? null);
        static::assertArrayHasKey('lng', $zone['coordinates'][0] ?? null);
        static::assertInternalType('float', $zone['coordinates'][0]['lat'] ?? null);
        static::assertInternalType('float', $zone['coordinates'][0]['lng'] ?? null);
        static::assertInternalType('int', $zone['zone_type'] ?? null);
        static::assertInternalType('int', $zone['faction'] ?? null);
    }

    /**
     * @dataProvider provideRolesToFetchMap
     */
    public function testMapTemplates(string $role)
    {
        $data = $this->getMapData($role);

        static::assertContains('id="marker_popup_name"', $data['templates']['LeafletPopupMarkerBaseContent'] ?? null);
        static::assertContains('id="polyline_popup_name"', $data['templates']['LeafletPopupPolylineBaseContent'] ?? null);
        static::assertContains('id="polygon_popup_name"', $data['templates']['LeafletPopupPolygonBaseContent'] ?? null);
    }

    /**
     * @dataProvider provideRolesToFetchMap
     */
    public function testMapMarkersTypes(string $role)
    {
        $data = $this->getMapData($role);

        $type = $data['references']['markers_types'][1] ?? null;
        static::assertSame('City', $type['name'] ?? null);
        $typeKeys = ['id', 'name', 'description', 'icon', 'icon_width', 'icon_height', 'icon_center_x', 'icon_center_y'];
        foreach ($typeKeys as $key) {
            static::assertArrayHasKey($key, $type);
        }
        static::assertInternalType('int', $type['icon_width'] ?? null);
        static::assertInternalType('int', $type['icon_height'] ?? null);
    }

    /**
     * @dataProvider provideRolesToFetchMap
     */
    public function testMapRoutesTypes(string $role)
    {
        $data = $this->getMapData($role);

        $type = $data['references']['routes_types'][1] ?? null;
        static::assertSame('Track', $type['name'] ?? null);
        $typeKeys = ['id', 'name', 'description', 'color'];
        foreach ($typeKeys as $key) {
            static::assertArrayHasKey($key, $type);
        }
        static::assertInternalType('string', $type['color'] ?? null);
    }

    /**
     * @dataProvider provideRolesToFetchMap
     */
    public function testMapZonesTypes(string $role)
    {
        $data = $this->getMapData($role);

        $type = $data['references']['zones_types'][2] ?? null;
        static::assertSame('Kingdom', $type['name'] ?? null);
        $typeKeys = ['id', 'name', 'description', 'color', 'parent_id'];
        foreach ($typeKeys as $key) {
            static::assertArrayHasKey($key, $type);
        }
        static::assertInternalType('string', $type['color'] ?? null);
        static::assertInternalType('int', $type['parent_id'] ?? null);
    }

    /**
     * @dataProvider provideRolesToFetchMap
     */
    public function testMapFactions(string $role)
    {
        $data = $this->getMapData($role);

        $type = $data['references']['factions'][1] ?? null;
        static::assertSame('Faction Test', $type['name'] ?? null);
        $typeKeys = ['id', 'name', 'description'];
        foreach ($typeKeys as $key) {
            static::assertArrayHasKey($key, $type);
        }
    }

    public function testCorahnRinMap()
    {
        $client = $this->getClient('corahnrin.esteren.docker');

        $client->request('GET', '/fr/api/maps/corahn_rin/1');

        $response = $client->getResponse();
        static::assertSame(200, $response->getStatusCode());
        $jsonContent = $response->getContent();
        $data = \json_decode($jsonContent, true);

        if (\json_last_error()) {
            static::fail(\json_last_error_msg());
        }

        static::assertSame(1, $data['map']['id'] ?? null);
        static::assertSame('tri-kazel', $data['map']['name_slug'] ?? null);
        static::assertInternalType('array', $data['map']['bounds'] ?? null);
        $mapKeys = [
            'id', 'name', 'name_slug', 'image', 'description', 'max_zoom', 'start_zoom', 'start_x', 'start_y',
            'bounds', 'coordinates_ratio', 'markers', 'routes', 'zones',
        ];
        foreach ($mapKeys as $key) {
            static::assertArrayHasKey($key, $data['map']);
        }

        static::assertSame([], $data['map']['routes']);
        static::assertSame([], $data['map']['markers']);
        static::assertSame([], $data['references']['markers_types']);
        static::assertSame([], $data['references']['routes_types']);
        static::assertSame([], $data['references']['transports']);

        $element = new Crawler($data['templates']['LeafletPopupMarkerBaseContent']);
        static::assertCount(1, $element->filter('h3#marker_popup_name'));
        static::assertCount(1, $element->filter('p#marker_popup_type'));

        $element = new Crawler($data['templates']['LeafletPopupPolylineBaseContent']);
        static::assertCount(1, $element->filter('h3#polyline_popup_name'));
        static::assertCount(1, $element->filter('p#polyline_popup_type'));

        $element = new Crawler($data['templates']['LeafletPopupPolygonBaseContent']);
        static::assertCount(1, $element->filter('h3#polygon_popup_name'));
        static::assertCount(1, $element->filter('p#polygon_popup_type'));
    }

    private function getMapData(string $role)
    {
        $client = $this->getClient('maps.esteren.docker');

        static::setToken($client, 'map-allowed', [$role]);

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
