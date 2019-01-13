<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Main\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UploadedAssetsUrlExtension extends AbstractExtension
{
    private $portalElementUploadPath;

    public function __construct(string $portalElementUploadPath)
    {
        $this->portalElementUploadPath = $portalElementUploadPath;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('uploaded_asset_url', [$this, 'assetUrl']),
        ];
    }

    public function assetUrl(string $assetName)
    {
        return $this->portalElementUploadPath.'/'.$assetName;
    }
}
