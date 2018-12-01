<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Admin\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Main\DependencyInjection\PublicService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("has_role('ROLE_ADMIN')")
 */
class AdminController extends BaseAdminController implements PublicService
{
    /**
     * @Route(
     *     "/{entity}/{action}/{id}",
     *     name="easyadmin",
     *     methods={"GET", "POST", "DELETE"},
     *     defaults={
     *         "entity": null,
     *         "action": null,
     *         "id": null
     *     }
     * )
     */
    public function indexAction(Request $request, string $entity = null, string $action = null, string $id = null)
    {
        if (!$id && \in_array($action, ['delete', 'show', 'edit'])) {
            throw $this->createNotFoundException('An id must be specified for this action.');
        }

        return parent::indexAction($request);
    }

    protected function redirectToBackendHomepage()
    {
        return $this->render('easy_admin/backend_homepage.html.twig');
    }
}
