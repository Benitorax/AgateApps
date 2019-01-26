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

namespace CorahnRin\Twig;

use CorahnRin\Data\DomainsData;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DomainsExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('get_domain_as_object', [DomainsData::class, 'getAsObject']),
        ];
    }
}
