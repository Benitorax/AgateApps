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

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Main\DependencyInjection\PublicService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("has_role('ROLE_MANAGER')")
 */
class AdminController extends BaseAdminController implements PublicService
{
    /**
     * @Route("/", name="easyadmin", methods={"GET", "POST", "DELETE"})
     * {@inheritdoc}
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
    }

    protected function joinBooks(QueryBuilder $qb): QueryBuilder
    {
        $qb->leftJoin('entity.book', 'book')->addSelect('book');

        return $qb;
    }
}
