<?php

declare(strict_types=1);

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\Controller\Admin;

use EsterenMaps\Entity\Maps;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("has_role('ROLE_ADMIN')")
 * @Route(host="%esteren_domains.backoffice%")
 */
class EditMapInteractiveController extends AbstractController
{
    private $tileSize;

    public function __construct($tileSize)
    {
        $this->tileSize = (int) $tileSize;
    }

    /**
     * @Route("/maps/edit-interactive/{id}", name="admin_esterenmaps_maps_maps_editInteractive", methods={"GET"})
     */
    public function editAction(Request $request, Maps $map): Response
    {
        if (\count($request->query->all())) {
            // To avoid polluting the URL with useless query string.
            return $this->redirectToRoute($request->attributes->get('_route'), $request->attributes->get('_route_params'));
        }

        return $this->render('esteren_maps/AdminMaps/edit.html.twig', [
            'map' => $map,
            'tile_size' => $this->tileSize,
        ]);
    }
}
