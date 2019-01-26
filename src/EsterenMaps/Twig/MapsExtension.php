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

namespace EsterenMaps\Twig;

use EsterenMaps\Repository\MapsRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MapsExtension extends AbstractExtension
{
    private $repository;

    public function __construct(MapsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_menu_maps', [$this->repository, 'findForMenu']),
        ];
    }
}
