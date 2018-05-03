<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\EsterenMaps\Services;

use Doctrine\ORM\EntityManagerInterface;
use EsterenMaps\Controller\Api\ApiDirectionsController;
use EsterenMaps\Entity\Maps;
use EsterenMaps\Entity\Markers;
use EsterenMaps\Entity\TransportTypes;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Tests\WebTestCase as PiersTestCase;

class DirectionsManagerTest extends WebTestCase
{
    use PiersTestCase;

    /**
     * @dataProvider provideWorkingDirections
     *
     * @param array  $expectedData
     * @param int    $map
     * @param string $from
     * @param string $to
     * @param null   $transport
     */
    public function testWorkingDirections(array $expectedData, $map, $from, $to, $transport = null)
    {
        static::resetDatabase();

        $kernel = static::bootKernel(['debug' => true]);

        $container = $kernel->getContainer();

        $em = $container->get(EntityManagerInterface::class);

        /** @var ApiDirectionsController $directions */
        $directions = $container->get(ApiDirectionsController::class);

        /** @var Maps $map */
        $map = $em->getRepository(Maps::class)->findOneBy(['nameSlug' => $map]);

        $markersRepo = $em->getRepository(Markers::class);

        /** @var Markers $from */
        $from = $markersRepo->find($from);

        /** @var Markers $to */
        $to = $markersRepo->find($to);

        $request = new Request();
        $request->headers->addCacheControlDirective('no-cache');
        $request->query->set('hours_per_day', 7);

        if ($transport) {
            $transport = $em->getRepository(TransportTypes::class)->findOneBy(['slug' => $transport]);
            $request->query->set('transport', $transport->getId());
        }

        $response = $directions->getDirectionsAction($map, $from, $to, $request);

        $dirs = json_decode($response->getContent(), true);

        foreach ($expectedData as $key => $expectedValue) {
            static::assertArrayHasKey($key, $dirs);
            if (array_key_exists($key, $dirs)) {
                static::assertSame($expectedValue, $dirs[$key], 'Json response key "'.$key.'" has invalid value.');
            }
        }
    }

    /**
     * Syntax:
     * > Expected output values (will check only these ones, if there are others, we don't check it).
     * > Map slug
     * > FROM Marker name
     * > TO marker name
     * > WITH Transport slug (can be null)
     *
     * @return array[]
     */
    public function provideWorkingDirections()
    {
        return [
            // Test from bottom left to top right with no transport (similar to "default")
            0 => [
                [
                    'found'           => true,
                    'from_cache'      => false,
                    'number_of_steps' => 16,
                    'duration_raw'    => null,
                    'duration_real'   => [
                        'days' => null,
                        'hours' => null,
                    ],
                ],
                'tri-kazel',
                76, // Pointe de Hòb
                40, // Col de Gaos-Bodhar
                null,
            ],
            // Test from bottom left to top right with "default" transport
            1 => [
                [
                    'found'           => true,
                    'from_cache'      => false,
                    'number_of_steps' => 16,
                ],
                'tri-kazel',
                76, // Pointe de Hòb
                40, // Col de Gaos-Bodhar
                'transport-par-defaut',
            ],
            // Test from bottom left to top right with ground transport
            2 => [
                [
                    'found'           => true,
                    'from_cache'      => false,
                    'number_of_steps' => 18,
                ],
                'tri-kazel',
                76, // Pointe de Hòb
                40, // Col de Gaos-Bodhar
                'chariot',
            ],
            // Test small ship transport with only routes in the sea
            3 => [
                [
                    'found'           => true,
                    'from_cache'      => false,
                    'number_of_steps' => 2,
                ],
                'tri-kazel',
                7,  // Tuaille
                72, // Seòl
                'coracle',
            ],
            // Test the simple routes set up for test only
            // First, with no transport
            4 => [
                [
                    'found'           => true,
                    'from_cache'      => false,
                    'number_of_steps' => 0,
                    'total_distance'  => 70.710678118,
                    'duration_raw'    => null,
                    'duration_real'   => [
                        'days' => null,
                        'hours' => null,
                    ],
                    'bounds'          => [
                        'northEast' => [
                            'lat' => 10,
                            'lng' => 10,
                        ],
                        'southWest' => [
                            'lat' => 0,
                            'lng' => 0,
                        ],
                    ],
                ],
                'tri-kazel',
                700, // {0, 0}
                702, // {10, 10}
                null,
            ],
            // With water transport, should be exactly the same as with no transport, but with duration explained
            5 => [
                [
                    'found'           => true,
                    'from_cache'      => false,
                    'number_of_steps' => 0,
                    'total_distance'  => 70.710678118,
                    'duration_raw'    => 'P0Y0M0DT8H51M0S',
                    'duration_real'   => [
                        'days' => 1,
                        'hours' => 1.84,
                    ],
                    'bounds'          => [
                        'northEast' => [
                            'lat' => 10,
                            'lng' => 10,
                        ],
                        'southWest' => [
                            'lat' => 0,
                            'lng' => 0,
                        ],
                    ],
                ],
                'tri-kazel',
                700, // {0, 0}
                702, // {10, 10}
                'koggen',
            ],
            // With water transport that should be slower
            6 => [
                [
                    'found'           => true,
                    'from_cache'      => false,
                    'number_of_steps' => 0,
                    'total_distance'  => 70.710678118,
                    'duration_raw'    => 'P0Y0M0DT17H41M0S',
                    'duration_real'   => [
                        'days' => 2,
                        'hours' => 3.68,
                    ],
                    'bounds'          => [
                        'northEast' => [
                            'lat' => 10,
                            'lng' => 10,
                        ],
                        'southWest' => [
                            'lat' => 0,
                            'lng' => 0,
                        ],
                    ],
                ],
                'tri-kazel',
                700, // {0, 0}
                702, // {10, 10}
                'coracle',
            ],
            // With ground transport that should be way slower
            7 => [
                [
                    'found'           => true,
                    'from_cache'      => false,
                    'number_of_steps' => 0,
                    'total_distance'  => 300,
                    'duration_raw'    => 'P0Y0M1DT13H30M0S',
                    'duration_real'   => [
                        'days' => 5,
                        'hours' => 2.5,
                    ],
                    'bounds'          => [
                        'northEast' => [
                            'lat' => 20,
                            'lng' => 10,
                        ],
                        'southWest' => [
                            'lat' => 0,
                            'lng' => -10,
                        ],
                    ],
                ],
                'tri-kazel',
                700, // {0, 0}
                702, // {10, 10}
                'chariot',
            ],
            // With ground transport and with one step
            8 => [
                [
                    'found'           => true,
                    'from_cache'      => false,
                    'number_of_steps' => 1,
                    'total_distance'  => 350,
                    'duration_raw'    => 'P0Y0M1DT19H45M0S',
                    'duration_real'   => [
                        'days' => 6,
                        'hours' => 1.75,
                    ],
                    'bounds'          => [
                        'northEast' => [
                            'lat' => 20,
                            'lng' => 10,
                        ],
                        'southWest' => [
                            'lat' => 0,
                            'lng' => -10,
                        ],
                    ],
                ],
                'tri-kazel',
                701, // {0, 10}
                702, // {10, 10}
                'chariot',
            ],
            // Should not be found, as no route match (test route to esteren maps route, no route between)
            9 => [
                [
                    'found'           => false,
                    'from_cache'      => false,
                    'number_of_steps' => 0,
                    'total_distance'  => null,
                    'duration_raw'    => null,
                    'duration_real'   => [
                        'days' => null,
                        'hours' => null,
                    ],
                ],
                'tri-kazel',
                700, // {0, 0}
                76,  // Pointe de Hòb
                null,
            ],
        ];
    }
}
