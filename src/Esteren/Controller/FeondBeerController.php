<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Esteren\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeondBeerController extends AbstractController
{
    /**
     * @Route("/feond-beer", name="esteren_portal_feond_beer", methods={"GET"})
     */
    public function feondBeerPortalAction(): Response
    {
        $response = new Response();
        $response->setCache([
            'max_age' => 600,
            's_maxage' => 3600,
            'public' => true,
        ]);

        return $this->render('esteren/feond_beer.html.twig', [], $response);
    }
}
