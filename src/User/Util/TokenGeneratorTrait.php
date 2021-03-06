<?php

declare(strict_types=1);

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace User\Util;

trait TokenGeneratorTrait
{
    public function generateToken()
    {
        return \rtrim(\strtr(\base64_encode(\random_bytes(32)), '+/', '-_'), '=');
    }
}
