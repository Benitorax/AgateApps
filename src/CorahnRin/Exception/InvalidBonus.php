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

namespace CorahnRin\Exception;

use CorahnRin\Entity\CharacterProperties\Bonuses;
use InvalidArgumentException;

class InvalidBonus extends InvalidArgumentException
{
    public function __construct(string $Domain)
    {
        parent::__construct(\sprintf(
            'Provided bonus "%s" is not valid. Possible values: %s',
            $Domain, \implode(', ', \array_keys(Bonuses::ALL))
        ));
    }
}
