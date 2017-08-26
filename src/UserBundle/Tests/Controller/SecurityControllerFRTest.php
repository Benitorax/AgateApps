<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UserBundle\Tests\Controller;

class SecurityControllerFRTest extends AbstractSecurityControllerTest
{
    protected function getLocale(): string
    {
        return 'fr';
    }
}
