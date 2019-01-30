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

namespace Tests\Main\Twig;

use Main\Twig\FlashMessageColorsExtension;
use PHPUnit\Framework\TestCase;

class FlashMessageColorsExtensionTest extends TestCase
{
    /**
     * @dataProvider provide flash messages
     */
    public function test flash messages classes(string $expected, string $input): void
    {
        static::assertSame($expected, $this->getExtension()->getFlashClass($input));
    }

    public function provide flash messages()
    {
        yield ['red lighten-3 red-text text-darken-4', 'alert'];
        yield ['red lighten-3 red-text text-darken-4', 'error'];
        yield ['red lighten-3 red-text text-darken-4', 'danger'];
        yield ['orange lighten-3 orange-text text-darken-4', 'warning'];
        yield ['teal lighten-3 teal-text text-darken-3', 'info'];
        yield ['green lighten-3 green-text text-darken-4', 'success'];
        yield ['', 'not_implemented_should_return_empty_string'];
    }

    private function getExtension()
    {
        return new FlashMessageColorsExtension();
    }
}
