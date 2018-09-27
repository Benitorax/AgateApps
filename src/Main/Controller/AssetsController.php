<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Main\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssetsController extends AbstractController
{
    private $debug;
    private $versionCode;
    private $versionDate;

    public function __construct(bool $debug, string $versionCode, string $versionDate)
    {
        $this->debug = $debug;
        $this->versionCode = $versionCode;
        $this->versionDate = $versionDate;
    }

    /**
     * @Route("/js/translations", name="pierstoval_tools_assets_jstranslations", host="%agate_domains.portal%", methods={"GET"})
     */
    public function jsTranslationsAction(Request $request, $_locale): Response
    {
        $response = new Response();
        if (!$this->debug) {
            $response->setCache([
                'etag' => \sha1('js'.$_locale.$this->versionCode),
                'last_modified' => new \DateTime($this->versionDate),
                'max_age' => 600,
                's_maxage' => 3600,
                'public' => true,
            ]);
        }

        if ($response->isNotModified($request)) {
            return $response;
        }

        $response->headers->add(['Content-type' => 'application/javascript']);

        return $this->render('agate/assets/js_translations.js.twig', [
            'locale' => $_locale,
        ], $response);
    }
}
