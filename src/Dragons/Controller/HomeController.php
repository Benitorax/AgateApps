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

use Agate\Exception\PortalElementNotFound;
use Agate\Repository\PortalElementRepository;
use Main\DependencyInjection\PublicService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @Route(host="%dragons_domains.portal%")
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
     * @Route("/", name="dragons_home", methods={"GET"})
     */
    public function indexAction(string $_locale): Response
    {
        $portalElement = $this->portalElementRepository->findForHomepage($_locale, 'dragons');

        if (!$portalElement) {
            throw new PortalElementNotFound('dragons', $_locale);
        }

        $response = new Response();
        $response->setCache([
            'max_age' => 3600,
            's_maxage' => 3600,
        ]);

        $template = 'dragons/index-'.$_locale.'.html.twig';

        return $response->setContent($this->twig->render($template, [
            'portal_element' => $portalElement,
        ]));
    }
}
