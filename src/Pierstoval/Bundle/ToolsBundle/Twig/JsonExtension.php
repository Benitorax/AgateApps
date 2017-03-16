<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pierstoval\Bundle\ToolsBundle\Twig;

class JsonExtension extends \Twig_Extension
{
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'pierstoval_tools_twig_json';
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('json_decode', [$this, 'jsonDecode']),
            new \Twig_SimpleFilter('json_encode', [$this, 'jsonEncode']),
        ];
    }

    /**
     * @param string $str
     * @param bool   $object
     *
     * @return mixed
     */
    public function jsonDecode($str, $object = false)
    {
        return json_decode($str, $object);
    }

    /**
     * @param array $array
     * @param int   $flags
     *
     * @return string
     */
    public function jsonEncode($array, $flags = 480)
    {
        return json_encode($array, $flags);
    }
}
