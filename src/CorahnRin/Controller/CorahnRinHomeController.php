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

namespace CorahnRin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CorahnRinHomeController extends AbstractController
{
    /**
     * @Route("/", name="corahn_rin_home", methods={"GET"})
     */
    public function indexAction(): Response
    {
        return $this->render('corahn_rin/home/index.html.twig');
    }
}
