<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Esteren\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeondBeerController extends Controller
{
    /**
     * @Route("/feond-beer", name="esteren_portal_feond_beer", methods={"GET"})
     */
    public function feondBeerPortalAction(Request $request): Response
    {
        $response = new Response();
        if (!$this->getParameter('kernel.debug')) {
            $response->setCache([
                'max_age' => 600,
                's_maxage' => 3600,
                'public' => true,
            ]);
        }

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $this->render('esteren/feond_beer.html.twig', [], $response);
    }
}
