<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dragons\Controller;

use Agate\Entity\PortalElement;
use Agate\Exception\PortalElementNotFound;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(host="%dragons_domains.portal%")
 */
class HomeController extends Controller
{
    /**
     * @Route("/", name="dragons_home", methods={"GET"})
     */
    public function indexAction(string $_locale): Response
    {
        $portalElement = $this->getDoctrine()->getRepository(PortalElement::class)->findOneBy([
            'locale' => $_locale,
            'portal' => 'dragons',
        ]);

        if (!$portalElement) {
            throw new PortalElementNotFound('dragons', $_locale);
        }

        $response = new Response();
        $response->setCache([
            'max_age' => 600,
            's_maxage' => 600,
            'public' => $this->getUser() ? false : true,
        ]);

        $template = 'dragons/index-'.$_locale.'.html.twig';

        return $this->render($template, [
            'portal_element' => $portalElement,
        ], $response);
    }
}
