<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Data;

final class ItemAvailability
{
    public const COMMON      = 'CO';
    public const FREQUENT    = 'FR';
    public const RARE        = 'RA';
    public const EXCEPTIONAL = 'EX';

    public static const ALL = [
        self::COMMON      => 'availability.common',
        self::FREQUENT    => 'availability.frequent',
        self::RARE        => 'availability.rare',
        self::EXCEPTIONAL => 'availability.exceptional',
    ];
}
