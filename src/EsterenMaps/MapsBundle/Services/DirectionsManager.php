<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\MapsBundle\Services;

use Doctrine\ORM\EntityManager;
use EsterenMaps\MapsBundle\Api\MapApi;
use EsterenMaps\MapsBundle\Cache\CacheManager;
use EsterenMaps\MapsBundle\Entity\Maps;
use EsterenMaps\MapsBundle\Entity\Markers;
use EsterenMaps\MapsBundle\Entity\TransportModifiers;
use EsterenMaps\MapsBundle\Entity\TransportTypes;
use Symfony\Component\Serializer\Serializer;
use Symfony\Bundle\TwigBundle\TwigEngine;

/**
 * Uses Dijkstra algorithm to calculate a path between two markers.
 */
class DirectionsManager
{
    private $debug;
    private $entityManager;
    private $serializer;
    private $templating;
    private $cache;
    private $mapApi;

    public function __construct(
        bool $debug,
        MapApi $mapApi,
        EntityManager $entityManager,
        Serializer $serializer,
        TwigEngine $templating,
        CacheManager $cache
    )
    {
        $this->debug         = $debug;
        $this->entityManager = $entityManager;
        $this->serializer    = $serializer;
        $this->templating    = $templating;
        $this->cache         = $cache;
        $this->mapApi        = $mapApi;
    }

    public function getDirections(Maps $map, Markers $start, Markers $end, int $hoursPerDay = 7, TransportTypes $transportType = null): array
    {
        $cacheHash = $this->generateDirectionHash($map, $start, $end, $transportType);

        $cacheItem = $this->cache->getItem('api.directions');

        // Get cache only in prod.
        if (
            !$this->debug
            && null !== $cacheItem
            && $cacheItem->isHit()
            && $jsonString = $this->cache->getItemValue($cacheItem, $cacheHash)
        ) {
            $directions               = json_decode($jsonString, true);
            $directions['from_cache'] = true;
        } else {
            $directions = $this->doGetDirections($map, $start, $end, $hoursPerDay, $transportType);

            // Save in the cache file
            $cacheData = $cacheItem->get() ?: [];
            $cacheData[$cacheHash] = json_encode($directions);
            $cacheItem->set($cacheData);
            $this->cache->saveItem($cacheItem);

            $directions['from_cache'] = false;
        }

        return $directions;
    }

    private function doGetDirections(Maps $map, Markers $start, Markers $end, int $hoursPerDay = 7, TransportTypes $transportType = null): array
    {
        $data = $this->mapApi->getMap($map->getId());

        // For performances & memory, let's remove this key, it's useless here.
        unset($data['templates']);

        $nodes = [];
        $edges = [];

        /*
         * Reformat nodes and edges for a better use of the Dijkstra algorithm.
         * We here have a list of "start" and "end" markers, and routes.
         * We need nodes (markers) and edges (routes).
         */
        foreach ($data['map']['routes'] as $routeId => $route) {
            $markerStartId = $route['marker_start'];
            $markerEndId   = $route['marker_end'];

            // Create an edge based on a route.
            $edge = [
                'id'       => $routeId,
                'distance' => $route['distance'],
                'start'    => $markerStartId,
                'end'      => $markerEndId,
            ];

            // Add nodes and edge.
            if ($markerStartId) {
                // Set the start node if does not exist.
                if (!array_key_exists($markerStartId, $nodes)) {
                    $nodes[$markerStartId] = [
                        'id'         => $markerStartId,
                        'name'       => $data['map']['markers'][$markerStartId]['name'],
                        'neighbours' => [],
                    ];
                }

                $nodes[$markerStartId]['neighbours'][$routeId] = [
                    'distance' => $route['distance'],
                    'end'      => $route['marker_end'],
                ];
            }
            if ($markerEndId) {
                // Set the end node if does not exist.
                if (!array_key_exists($markerEndId, $nodes)) {
                    $nodes[$markerEndId] = [
                        'id'         => $markerEndId,
                        'name'       => $data['map']['markers'][$markerEndId]['name'],
                        'neighbours' => [],
                    ];
                }

                $nodes[$markerEndId]['neighbours'][$routeId] = [
                    'distance' => $route['distance'],
                    'end'      => $markerStartId,
                ];
            }
            $edges[$routeId] = $edge;
        }

        $paths = $this->dijkstra($nodes, $edges, $start->getId(), $end->getId());

        $routesIds    = array_values($paths);
        $markersArray = array_filter($data['map']['markers'], function($marker) use ($paths) {
            return array_key_exists($marker['id'], $paths);
        });
        $routesArray = array_filter($data['map']['routes'], function($route) use ($routesIds) {
            return in_array($route['id'], $routesIds, true);
        });

        $paths = $this->checkTransportType($paths, $routesArray, $transportType);

        $steps = [];

        // Remove unused fields
        foreach ($paths as $markerId => $routeId) {
            $marker                        = $markersArray[$markerId];
            $marker['route']               = $routeId ? $routesArray[$routeId] : null;
            $marker['marker_type']         = $marker['marker_type'] ? $data['references']['markers_types'][$marker['marker_type']] : null;
            $marker['faction']             = $marker['faction'] ? $data['references']['factions'][$marker['faction']] : null;
            if (isset($marker['route'])) {
                $marker['route']['faction']    = $marker['route']['faction'] ? $data['references']['factions'][$marker['route']['faction']] : null;
                $marker['route']['route_type'] = $marker['route']['route_type'] ? $data['references']['routes_types'][$marker['route']['route_type']] : null;
            }
            unset(
                $marker['route']['marker_start'],
                $marker['route']['marker_end']
            );
            $steps[] = $marker;
        }

        return $this->getDataArray($start, $end, $steps, $routesArray, $hoursPerDay, $transportType);
    }

    /**
     * Applies Dijkstra algorithm to calculate minimal distance between source and target.
     *
     * Implementation of http://codereview.stackexchange.com/questions/75641/dijkstras-algorithm-in-php
     *
     * @see http://codereview.stackexchange.com/questions/75641/dijkstras-algorithm-in-php
     *
     * Return an array where keys are the markers IDs and values the values are route IDs.
     */
    private function dijkstra(array $nodes, array $edges, int $start, int $end): array
    {
        /** @var array[][] $distances */
        $distances = [];

        foreach ($nodes as $id => $node) {
            foreach ($node['neighbours'] as $nid => $neighbour) {
                $distances[$id][$neighbour['end']] = [
                    'edge'     => $edges[$nid],
                    'distance' => $neighbour['distance'],
                ];
            }
        }

        //initialize the array for storing
        $S = []; //the nearest path with its parent and weight
        $Q = []; //the left nodes without the nearest path

        $distanceKeys = array_keys($distances);
        foreach ($distanceKeys as $val) {
            $Q[$val] = INF;
        }
        $Q[$start] = 0;

        //start calculating
        while (0 !== count($Q)) {
            $min = array_search(min($Q), $Q, true); //the most min weight
            if ($min === $end) {
                break;
            }
            if (!array_key_exists($min, $distances)) {
                // In the case the route ID does not exist, we set it as empty.
                // It can only happen if the transport selected "removes" some inaccessible routes,
                //  specificly when the route type has a speed modifier of 0 with this transport.
                $distances[$min] = [];
            }
            foreach ($distances[$min] as $key => $val) {
                $dist = $val['distance'];

                if (!empty($Q[$key]) && $Q[$min] + $dist < $Q[$key]) {
                    $Q[$key] = $Q[$min] + $dist;
                    $S[$key] = [$min, $Q[$key]];
                }
            }
            unset($Q[$min]);
        }

        if (!array_key_exists($end, $S)) {
            return [];
        }

        $path = [];
        $pos  = $end;
        while ($pos !== $start) {
            $path[] = $pos;
            $pos    = $S[$pos][0];
        }
        $path[] = $start;
        $path   = array_reverse($path);

        $realPath = [];

        foreach ($path as $k => $nodeId) {
            $next = $path[$k + 1] ?? null;

            $realPath[$nodeId] = null;

            if ($next) {
                $dist     = INF;
                $realEdge = null;

                foreach ($nodes[$nodeId]['neighbours'] as $edgeId => $edge) {
                    if ($edge['distance'] < $dist && $edge['end'] === $next) {
                        $realEdge = $edges[$edgeId];
                        $dist     = $edge['distance'];
                    }
                }

                if ($realEdge) {
                    $realPath[$nodeId] = $realEdge['id'];
                }
            }
        }

        return $realPath;
    }

    private function getDataArray(Markers $from, Markers $to, array $directions, array $routes, int $hoursPerDay = 7, TransportTypes $transport = null): array
    {
        $distance = 0;
        $NE       = [];
        $SW       = [];

        foreach ($directions as $step) {
            $distance += ($step['route'] ? $step['route']['distance'] : 0);
            if ($step['route']) {
                /** @var array $coords */
                $coords = $step['route']['coordinates'];
                foreach ($coords as $latLng) {
                    if (!isset($NE['lat']) || ($NE['lat'] < $latLng['lat'])) {
                        $NE['lat'] = $latLng['lat'];
                    }
                    if (!isset($NE['lng']) || ($NE['lng'] < $latLng['lng'])) {
                        $NE['lng'] = $latLng['lng'];
                    }
                    if (!isset($SW['lat']) || ($SW['lat'] > $latLng['lat'])) {
                        $SW['lat'] = $latLng['lat'];
                    }
                    if (!isset($SW['lng']) || ($SW['lng'] > $latLng['lng'])) {
                        $SW['lng'] = $latLng['lng'];
                    }
                }
            }
        }

        $data = [
            'found'           => count($directions) > 0,
            'path_view'       => null,
            'duration_raw'    => null,
            'duration_real'   => null,
            'transport'       => $transport ? $transport->jsonSerialize() : null,
            'bounds'          => ['northEast' => $NE, 'southWest' => $SW],
            'total_distance'  => $distance,
            'number_of_steps' => count($directions) ? (count($directions) - 2) : 0,
            'start'           => $from->jsonSerialize(),
            'end'             => $to->jsonSerialize(),
            'path'            => $directions,
        ];

        $data['duration_raw']  = ['days' => null, 'hours' => null];
        $data['duration_real'] = ['days' => null, 'hours' => null];

        if ($transport) {
            $data['duration_raw']  = $this->getTravelDuration($routes, $transport, $hoursPerDay);
            $data['duration_real'] = $this->getTravelDuration($routes, $transport, $hoursPerDay, false);
        }

        $data['path_view'] = $this->templating->render('@EsterenMaps/Api/path_view.html.twig', $data);

        return $data;
    }

    /**
     * @param array          $paths
     * @param array[]        $routes
     * @param TransportTypes $transportType
     *
     * @return array
     */
    private function checkTransportType(array $paths, array $routes, TransportTypes $transportType = null): array
    {
        if (!$transportType) {
            return $paths;
        }

        $routesTypes = [];

        foreach ($routes as $route) {
            $routesTypes[$route['route_type']] = $route['route_type'];
        }

        $transportModifiers = $this->entityManager->getRepository(TransportModifiers::class)->findBy([
            'routeType' => $routesTypes,
            'transportType' => $transportType,
        ]);

        // Check that the transport have a good value here
        foreach ($transportModifiers as $modifier) {
            if ($modifier->getPercentage() <= 0) {
                return [];
            }
        }

        return $paths;
    }

    /**
     * @return int[]|string|null
     */
    private function getTravelDuration(array $routes, TransportTypes $transport, int $hoursPerDay = 7, bool $raw = true)
    {
        $total = 0;

        // Get (again) the necessary transport modifiers for the final route.
        $routesTypes = [];
        foreach ($routes as $route) {
            $routesTypes[$route['route_type']] = $route['route_type'];
        }
        $transportModifiersUnsorted = $this->entityManager->getRepository(TransportModifiers::class)->findBy([
            'routeType' => $routesTypes,
            'transportType' => $transport,
        ]);

        /** @var TransportModifiers[][] $transportModifiers */
        $transportModifiers = [];
        foreach ($transportModifiersUnsorted as $transportModifier) {
            $transportModifiers[$transportModifier->getRouteType()->getId()][] = $transportModifier;
        }

        foreach ($routes as $route) {
            $distance          = $route['distance'];
            $transportModifier = null;

            foreach ($transportModifiers[$route['route_type']] as $modifier) {
                if ($modifier->getTransportType()->getId() === $transport->getId()) {
                    $transportModifier = $modifier;
                    break;
                }
            }

            if ($transportModifier) {
                $percentage = (float) $transportModifier->getPercentage();
                if ($transportModifier->isPositiveRatio()) {
                    $speed = $transport->getSpeed() * ($percentage / 100);
                } else {
                    $speed = $transport->getSpeed() * ((100 - $percentage) / 100);
                }
                $hours = $distance / $speed;
                $total += $hours;
            }
        }

        $hours   = (int) floor($total);
        $minutes = (int) ceil(($total - $hours) * 100 * 60 / 100);

        $interval = new \DateInterval('PT'.$hours.'H'.$minutes.'M');
        $start    = new \DateTime();
        $end      = new \DateTime();
        $end->add($interval);

        // Recreating the interval allows automatic calculation of days/months.
        $interval = $start->diff($end);

        // Get the raw DateInterval format
        if ($raw) {
            return $interval->format('P%yY%mM%dDT%hH%iM0S');
        }

        // Here we'll try to convert hours into a more "realistic" travel time.
        $realisticDays = $total / $hoursPerDay;

        $days  = (int) floor($realisticDays);
        $hours = (float) number_format(($realisticDays - $days) * $hoursPerDay, 2);

        return [
            'days'  => $days,
            'hours' => $hours,
        ];
    }

    /**
     * Generates a unique hash based on all direction data.
     *
     * @param Maps                $map
     * @param Markers             $start
     * @param Markers             $end
     * @param TransportTypes|null $transportType
     *
     * @return string
     */
    private function generateDirectionHash(Maps $map, Markers $start, Markers $end, TransportTypes $transportType = null)
    {
        return md5($map->getId().$start->getId().$end->getId().($transportType ?: ''));
    }
}
