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

use User\Entity\User;

class UserAdminTest extends AbstractEasyAdminTest
{
    /**
     * {@inheritdoc}
     */
    public function getEntityName()
    {
        return 'Users';
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return User::class;
    }

    /**
     * {@inheritdoc}
     */
    public function provideListingFields()
    {
        return [
            'id',
            'username',
            'email',
            'roles',
            'emailConfirmed',
            'createdAt',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function provideNewFormData()
    {
        return [
            'data_to_submit' => $data = [
                'username' => 'new-user-from-admin',
                'email' => 'new-user-from-admin@domain.local',
            ],
            'search_data' => $data,
            'expected_data' => $data,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function provideEditFormData()
    {
        return false;
    }

    protected function getClient($host = null, array $kernelOptions = [], $tokenRoles = [], array $server = [])
    {
        $tokenRoles[] = 'ROLE_SUPER_ADMIN';

        return parent::getClient($host, $kernelOptions, $tokenRoles, $server);
    }
}
