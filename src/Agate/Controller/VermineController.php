<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Agate\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(host="%vermine_domains.portal%")
 */
class VermineController extends AbstractController
{
    /**
     * @Route("", name="vermine_portal_home", methods={"GET"})
     */
    public function indexAction(): Response
    {
        $response = new Response();
        $response->setCache([
            'max_age' => 600,
            's_maxage' => 3600,
            'public' => true,
        ]);

        return $this->render('agate/vermine/vermine-home.html.twig', [], $response);
    }
}
