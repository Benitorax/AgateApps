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

namespace CorahnRin\DTO;

use CorahnRin\Entity\Advantage;

class SessionAdvantageDTO
{
    /**
     * @var Advantage
     */
    private $advantage;

    /**
     * @var string
     */
    private $indication;

    private function __construct()
    {
        // Disable public constructor
    }

    public static function create(Advantage $advantage, string $indication): self
    {
        $self = new self();

        $self->advantage = $advantage;
        $self->indication = $indication;

        return $self;
    }
}
