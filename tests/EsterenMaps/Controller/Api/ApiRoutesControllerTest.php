<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\EsterenMaps\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\WebTestCase as PiersTestCase;

class ApiRoutesControllerTest extends WebTestCase
{
    use PiersTestCase;

    public function testCreateWithCorrectData()
    {
        static::resetDatabase();

        $client = $this->getClient('back.esteren.docker', [], 'ROLE_ADMIN');

        $data = [
            'name' => 'Test name',
            'description' => 'Test description',
            'coordinates' => '[{"lat":"0","lng":"10"}]',
            'forcedDistance' => null,
            'guarded' => false,
            'markerStart' => 700,
            'markerEnd' => 701,
            'map' => 1,
            'routeType' => 1,
            'faction' => null,
        ];

        $client->request('POST', '/fr/api/routes', [], [], [], \json_encode($data));

        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame('application/json', $client->getResponse()->headers->get('Content-Type'));

        // Add ID For assertion
        $data['id'] = 704;
        $responseData = \json_decode($client->getResponse()->getContent(), true);
        static::assertSame(\ksort($data), \ksort($responseData));
    }

    public function testCreateWithEmptyData()
    {
        static::resetDatabase();

        $client = $this->getClient('back.esteren.docker', [], 'ROLE_ADMIN');

        $client->request('POST', '/fr/api/routes', [], [], [], '[]');

        static::assertSame(400, $client->getResponse()->getStatusCode());
        static::assertSame('application/json', $client->getResponse()->headers->get('Content-Type'));

        $expectedResponse = [
            'name' => 'Cette valeur ne doit pas être vide.',
            'markerStart' => 'Cette valeur ne doit pas être vide.',
            'markerEnd' => 'Cette valeur ne doit pas être vide.',
            'map' => 'Cette valeur ne doit pas être vide.',
            'routeType' => 'Cette valeur ne doit pas être vide.',
        ];

        $responseData = \json_decode($client->getResponse()->getContent(), true);

        static::assertSame($expectedResponse, $responseData);
    }

    public function testCreateWithIncorrectData()
    {
        static::resetDatabase();

        $client = $this->getClient('back.esteren.docker', [], 'ROLE_ADMIN');

        $dataToSend = [
            'name' => 'Test name',
            'description' => 'Test description',
            'coordinates' => '[{"lat":"0","lng":"10"}]',
            'forcedDistance' => -10,
            'guarded' => false,
            'markerStart' => 9999999999,
            'markerEnd' => 9999999999,
            'map' => 9999999999,
            'routeType' => 9999999999,
            'faction' => 9999999999,
        ];

        $client->request('POST', '/fr/api/routes', [], [], [], \json_encode($dataToSend));

        static::assertSame(400, $client->getResponse()->getStatusCode());
        static::assertSame('application/json', $client->getResponse()->headers->get('Content-Type'));

        $responseData = \json_decode($client->getResponse()->getContent(), true);

        $expectedResponse = [
            'forcedDistance' => 'Cette valeur doit être supérieure ou égale à 0.',
            'markerStart' => 'Cette valeur ne doit pas être vide.',
            'markerEnd' => 'Cette valeur ne doit pas être vide.',
            'map' => 'Cette valeur ne doit pas être vide.',
            'routeType' => 'Cette valeur ne doit pas être vide.',
        ];

        static::assertSame($expectedResponse, $responseData);
    }
}
