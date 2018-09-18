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

class GoogleTagsExtension extends AbstractExtension
{
    /**
     * @var array
     */
    private $googleTags;

    /**
     * @var bool
     */
    private $debug;

    public function __construct(array $googleTags, $debug)
    {
        $this->googleTags = $googleTags;
        $this->debug = $debug;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('get_gtm', [$this, 'getGoogleTagManager']),
            new TwigFunction('get_ga', [$this, 'getGoogleAnalytics']),
        ];
    }

    /**
     * @return string
     */
    public function getGoogleTagManager()
    {
        return $this->debug ? null : $this->googleTags['tag_manager'];
    }

    /**
     * @return string
     */
    public function getGoogleAnalytics()
    {
        return $this->debug ? null : $this->googleTags['analytics'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'google_tags';
    }
}
