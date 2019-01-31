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

namespace Agate\Controller;

use Agate\Exception\PortalElementNotFound;
use Agate\Repository\PortalElementRepository;
use Main\DependencyInjection\PublicService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @Route(host="%agate_domains.portal%")
 */
class HomeController implements PublicService
{
    private $portalElementRepository;
    private $twig;

    public function __construct(PortalElementRepository $portalElementRepository, Environment $twig)
    {
        $this->portalElementRepository = $portalElementRepository;
        $this->twig = $twig;
    }

    /**
     * @Route("/", name="agate_portal_home", methods={"GET"})
     */
    public function indexAction(string $_locale): Response
    {
        $portalElement = $this->portalElementRepository->findForHomepage($_locale, 'agate');

        if (!$portalElement) {
            throw new PortalElementNotFound('agate', $_locale);
        }

        $response = new Response();
        $response->setCache([
            'max_age' => 3600,
            's_maxage' => 3600,
        ]);

        $template = 'agate/home/index-'.$_locale.'.html.twig';

        return $response->setContent($this->twig->render($template, [
            'portal_element' => $portalElement,
        ]));
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

        return $response->setContent($this->twig->render('agate/home/team.html.twig'));
    }
}
