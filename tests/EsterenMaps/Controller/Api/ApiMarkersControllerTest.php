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

class ApiMarkersControllerTest extends WebTestCase
{
    use PiersTestCase;

    public function testCreateWithCorrectData()
    {
        static::resetDatabase();

        $client = $this->getClient('back.esteren.docker', [], 'ROLE_ADMIN');

        $data = [
            'name' => 'Test name',
            'description' => 'Test description',
            'latitude' => 20,
            'longitude' => 20,
            'altitude' => 0,
            'map' => 1,
            'markerType' => 1,
            'faction' => null,
        ];

        $client->request('POST', '/fr/api/markers', [], [], [], \json_encode($data));

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

        $client->request('POST', '/fr/api/markers', [], [], [], '[]');

        static::assertSame(400, $client->getResponse()->getStatusCode());
        static::assertSame('application/json', $client->getResponse()->headers->get('Content-Type'));

        $expectedResponse = [
            'name' => 'Cette valeur ne doit pas être vide.',
            'map' => 'Cette valeur ne doit pas être vide.',
            'markerType' => 'Cette valeur ne doit pas être vide.',
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
            'latitude' => 20,
            'longitude' => 20,
            'altitude' => 0,
            'map' => 9999999999,
            'markerType' => 9999999999,
            'faction' => 9999999999,
        ];

        $client->request('POST', '/fr/api/markers', [], [], [], \json_encode($dataToSend));

        static::assertSame(400, $client->getResponse()->getStatusCode());
        static::assertSame('application/json', $client->getResponse()->headers->get('Content-Type'));

        $responseData = \json_decode($client->getResponse()->getContent(), true);

        $expectedResponse = [
            'map' => 'Cette valeur ne doit pas être vide.',
            'markerType' => 'Cette valeur ne doit pas être vide.',
        ];

        static::assertSame($expectedResponse, $responseData);
    }
}
