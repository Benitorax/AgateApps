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

use Agate\Entity\PortalElement;
use Agate\Exception\PortalElementNotFound;
use Main\DependencyInjection\PublicService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(host="%agate_domains.portal%")
 */
class HomeController extends AbstractController implements PublicService
{
    /**
     * @Route("/", name="agate_portal_home", methods={"GET"})
     */
    public function indexAction(string $_locale): Response
    {
        $portalElement = $this->getDoctrine()->getRepository(PortalElement::class)->findOneBy([
            'locale' => $_locale,
            'portal' => 'agate',
        ]);

        if (!$portalElement) {
            throw new PortalElementNotFound('agate', $_locale);
        }

        $response = new Response();
        $response->setCache([
            'max_age' => 600,
            's_maxage' => 3600,
            'public' => true,
        ]);

        $template = 'agate/home/index-'.$_locale.'.html.twig';

        return $this->render($template, [
            'portal_element' => $portalElement,
        ], $response);
    }

    /**
     * @Route("/team", name="agate_team", methods={"GET"})
     */
    public function teamAction(): Response
    {
        $response = new Response();

        $response->setCache([
            'max_age' => 600,
            's_maxage' => 3600,
            'public' => true,
        ]);

        return $this->render('agate/home/team.html.twig', [], $response);
    }
}
