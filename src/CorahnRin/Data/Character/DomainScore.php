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

namespace CorahnRin\Data\Character;

class DomainScore
{
    private $domain;

    private $wayScore;

    private $base;

    private $bonus;

    private $malus;

    public function __construct(
        string $domain,
        int $wayValue,
        int $base,
        int $bonus,
        int $malus
    ) {
        $this->domain = $domain;
        $this->wayScore = $wayValue;
        $this->base = $base;
        $this->bonus = $bonus;
        $this->malus = $malus;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getWayScore(): int
    {
        return $this->wayScore;
    }

    public function getBase(): int
    {
        return $this->base;
    }

    public function getBonus(): int
    {
        return $this->bonus;
    }

    public function getMalus(): int
    {
        return $this->malus;
    }

    public function getTotal(): int
    {
        return static::getTotalForValues($this->wayScore, $this->base, $this->bonus, $this->malus);
    }

    private static function getTotalForValues(
        int $wayValue,
        int $base,
        int $bonus,
        int $malus
    ): int {
        return $wayValue + $base + $bonus - $malus;
    }
}
