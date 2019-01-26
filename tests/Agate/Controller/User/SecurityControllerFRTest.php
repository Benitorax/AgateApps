<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Agate\Controller\User;

class SecurityControllerFRTest extends AbstractSecurityControllerTest
{
    protected function getLocale(): string
    {
        return 'fr';
    }
}
