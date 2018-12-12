<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Admin;

use EsterenMaps\Entity\Maps;

class MapsAdminTest extends AbstractEasyAdminTest
{
    /**
     * {@inheritdoc}
     */
    public function getEntityName()
    {
        return 'Maps';
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return Maps::class;
    }

    /**
     * {@inheritdoc}
     */
    public function provideListingFields()
    {
        return [
            'id',
            'name',
            'nameSlug',
            'maxZoom',
            'startZoom',
            'startX',
            'startY',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function provideNewFormData()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function provideEditFormData()
    {
        return false;
    }

    public function test edit interactive route returns 200()
    {
        $client = $this->getClient('back.esteren.docker', [], 'ROLE_ADMIN');

        $crawler = $client->request('GET', '/fr/maps/edit-interactive/1');

        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertCount(1, $crawler->filter('div#esterenmap_sidebar'));
        static::assertCount(1, $crawler->filter('div#map'));
    }
}
