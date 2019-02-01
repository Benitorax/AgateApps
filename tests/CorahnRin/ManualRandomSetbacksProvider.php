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

namespace Tests\CorahnRin;

use CorahnRin\GeneratorTools\RandomSetbacksProvider;

/**
 * This provider replaces the RandomSetbacksProvider in test so we can use it instead of the native one.
 *
 * The goal is to be able to control the "dice roll" that picks random setbacks.
 *
 * The default behavior of the RandomSetbacksProvider is to array_shift() a list of setbacks previously shuffled.
 *
 * By setting "custom setbacks to pick", they will be selected one by one without shuffling them,
 * so we can have a total control over what setbacks are picked.
 */
class ManualRandomSetbacksProvider extends RandomSetbacksProvider
{
    private $customSetbacksToPick;

    public function getRandomSetbacks(array $setbacksToSearchIn, int $numberOfSetbacksToPick): array
    {
        return parent::getRandomSetbacks($this->customSetbacksToPick ?: $setbacksToSearchIn, $numberOfSetbacksToPick);
    }

    protected function shuffle(array &$setbacksDiceList): void
    {
        if (!$this->customSetbacksToPick) {
            parent::shuffle($setbacksDiceList);
        }
        // Do nothing and keep the array as-is
    }

    public function setCustomSetbacksToPick(array $customSetbacksToPick): void
    {
        $this->customSetbacksToPick = $customSetbacksToPick;
    }
}
