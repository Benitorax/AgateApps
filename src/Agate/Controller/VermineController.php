<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Agate\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(host="%vermine_domains.portal%")
 */
class VermineController extends AbstractController
{
    private $versionDate;

    public function __construct($versionDate)
    {
        $this->versionDate = $versionDate;
    }

    /**
     * @Route("/", name="vermine_portal_home", methods={"GET"})
     */
    public function indexAction(Request $request): Response
    {
        $response = new Response();
        $response->setCache([
            'last_modified' => new \DateTime($this->versionDate),
            'max_age' => 600,
            's_maxage' => 3600,
            'public' => true,
        ]);

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $this->render('agate/vermine/vermine-home.html.twig', [], $response);
    }
}
