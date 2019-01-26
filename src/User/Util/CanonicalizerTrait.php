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

trait CanonicalizerTrait
{
    public function canonicalize(string $string): string
    {
        if (null === $string) {
            return null;
        }

        $encoding = \mb_detect_encoding($string, \mb_detect_order(), true);

        $result = $encoding
            ? \mb_convert_case($string, MB_CASE_LOWER, $encoding)
            : \mb_convert_case($string, MB_CASE_LOWER);

        return $result;
    }
}
