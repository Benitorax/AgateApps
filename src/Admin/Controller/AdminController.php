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

namespace Admin\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Main\DependencyInjection\PublicService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends EasyAdminController implements PublicService
{
    /**
     * @Route(
     *     "/{entity}/{action}/{id}",
     *     name="easyadmin",
     *     methods={"GET", "POST", "DELETE"},
     *     defaults={
     *         "entity" = null,
     *         "action" = null,
     *         "id" = null
     *     }
     * )
     */
    public function indexAction(Request $request, string $entity = null, string $action = null, string $id = null)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        if (!$id && \in_array($action, ['delete', 'show', 'edit'], true)) {
            throw $this->createNotFoundException('An id must be specified for this action.');
        }

        return parent::indexAction($request);
    }

    protected function redirectToBackendHomepage()
    {
        return $this->render('easy_admin/backend_homepage.html.twig');
    }
}
