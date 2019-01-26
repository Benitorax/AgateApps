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

namespace Esteren\Controller;

use Agate\Entity\PortalElement;
use Agate\Exception\PortalElementNotFound;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="esteren_portal_home", methods={"GET"})
     */
    public function indexAction(string $_locale, Request $request): Response
    {
        $portalElement = $this->getDoctrine()->getRepository(PortalElement::class)->findOneBy([
            'locale' => $_locale,
            'portal' => 'esteren',
        ]);

        if (!$portalElement) {
            throw new PortalElementNotFound('esteren', $_locale);
        }

        $template = 'esteren/index-'.$_locale.'.html.twig';

        return $this->render($template, [
            'portal_element' => $portalElement,
        ]);
    }
}
