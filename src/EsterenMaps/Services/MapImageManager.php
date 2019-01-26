<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\Services;

use EsterenMaps\Entity\Map;

class MapImageManager
{
    private $publicDir;

    public function __construct(string $publicDir)
    {
        $this->publicDir = $publicDir;
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

        return $this->publicDir.'/'.$path;
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
