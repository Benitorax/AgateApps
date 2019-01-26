<?php

declare(strict_types=1);

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dragons\Controller;

use Agate\Entity\PortalElement;
use Agate\Exception\PortalElementNotFound;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(host="%dragons_domains.portal%")
 */
class HomeController extends AbstractController
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
            's_maxage' => 3600,
        ]);

        $template = 'dragons/index-'.$_locale.'.html.twig';

        return $this->render($template, [
            'portal_element' => $portalElement,
        ], $response);
    }
}
