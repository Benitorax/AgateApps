<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\Services;

use EsterenMaps\Entity\Map;

class MapImageManager
{
    private $webDir;

    public function __construct(string $publicDir)
    {
        $this->webDir = $publicDir;
    }

    /**
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getImagePath(Map $map)
    {
        $ext = \pathinfo($map->getImage(), PATHINFO_EXTENSION);

        if (!$ext) {
            throw new \RuntimeException('Could not get map image extension. Got "'.$map->getImage().'".');
        }

        $path = \preg_replace('~\.'.$ext.'$~i', '_IM.'.$ext, $map->getImage());

        return $this->webDir.'/'.$path;
    }

    /**
     * @throws \RuntimeException
     */
    public function generateImage(Map $map)
    {
        // TODO
        throw new \RuntimeException('Not implemented yet');
    }
}
