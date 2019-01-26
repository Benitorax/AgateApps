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

namespace CorahnRin\Entity\CharacterProperties;

use CorahnRin\Data\Ways as WaysData;
use CorahnRin\Exception\InvalidWay;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Ways
{
    /**
     * @var int
     *
     * @ORM\Column(name="combativeness", type="integer")
     */
    protected $combativeness;

    /**
     * @var int
     *
     * @ORM\Column(name="creativity", type="integer")
     */
    protected $creativity;

    /**
     * @var int
     *
     * @ORM\Column(name="empathy", type="integer")
     */
    protected $empathy;

    /**
     * @var int
     *
     * @ORM\Column(name="reason", type="integer")
     */
    protected $reason;

    /**
     * @var int
     *
     * @ORM\Column(name="conviction", type="integer")
     */
    protected $conviction;

    public function __construct(
        int $combativeness,
        int $creativity,
        int $empathy,
        int $reason,
        int $conviction
    ) {
        $this->combativeness = $combativeness;
        $this->creativity = $creativity;
        $this->empathy = $empathy;
        $this->reason = $reason;
        $this->conviction = $conviction;
    }

    public function getWay(string $way): int
    {
        WaysData::validateWay($way);

        switch ($way) {
            case WaysData::COMBATIVENESS:
                return $this->combativeness;
            case WaysData::CREATIVITY:
                return $this->creativity;
            case WaysData::EMPATHY:
                return $this->empathy;
            case WaysData::REASON:
                return $this->reason;
            case WaysData::CONVICTION:
                return $this->conviction;
            default:
                throw new InvalidWay($way);
        }
    }

    public function getCombativeness(): int
    {
        return $this->combativeness;
    }

    public function getCreativity(): int
    {
        return $this->creativity;
    }

    public function getEmpathy(): int
    {
        return $this->empathy;
    }

    public function getReason(): int
    {
        return $this->reason;
    }

    public function getConviction(): int
    {
        return $this->conviction;
    }
}
