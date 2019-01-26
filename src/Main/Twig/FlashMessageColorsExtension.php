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

namespace Main\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FlashMessageColorsExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('get_flash_class', [$this, 'getFlashClass']),
        ];
    }

    public function getFlashClass($initialClass)
    {
        switch ($initialClass) {
            case 'alert':
            case 'error':
            case 'danger':
                return 'red lighten-3 red-text text-darken-4';
            case 'warning':
                return 'orange lighten-3 orange-text text-darken-4';
            case 'info':
                return 'teal lighten-3 teal-text text-darken-3';
            case 'success':
                return 'green lighten-3 green-text text-darken-4';
            default:
                return '';
        }
    }
}
