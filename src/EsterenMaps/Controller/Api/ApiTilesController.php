<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\Controller\Api;

use EsterenMaps\Entity\Maps;
use EsterenMaps\Form\MapImageType;
use EsterenMaps\Model\MapImageQuery;
use EsterenMaps\Services\MapsTilesManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiTilesController extends AbstractController
{
    private $outputDirectory;
    private $tilesManager;

    public function __construct(
        string $outputDirectory,
        MapsTilesManager $tilesManager
    ) {
        $this->outputDirectory = $outputDirectory;
        $this->tilesManager = $tilesManager;
    }

    /**
     * @Route("/maps/image/{id}", requirements={"id" = "\d+"}, name="esterenmaps_generate_map_image", methods={"GET"})
     */
    public function generateMapImageAction(Request $request, Maps $map): Response
    {
        $baseData = new MapImageQuery();
        $form = $this->createForm(new MapImageType(), $baseData);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $response = $this->handleFormSuccess($baseData, $map)) {
            return $response;
        }

        $messages = [];
        foreach ($form->getErrors(true) as $error) {
            $field = $error->getOrigin()->getName();
            $messages[] = $this->get('translator')->trans('field_error', ['%field%' => $field], 'validators').': '.$error->getMessage();
        }

        return new JsonResponse([
            'error' => true,
            'message' => $messages,
        ], 400);
    }

    /**
     * @Route(
     *     "/maps/tile/{id}/{zoom}/{x}/{y}.jpg",
     *     requirements={
     *         "id" = "\d+",
     *         "zoom" = "\d+",
     *         "x" = "\d+",
     *         "y" = "\d+"
     *     },
     *     name="esterenmaps_api_tiles",
     *     methods={"GET"}
     * )
     */
    public function tileAction(Maps $map, int $zoom, int $x, int $y): Response
    {
        $file = $this->outputDirectory.$map->getId().'/'.$zoom.'/'.$x.'/'.$y.'.jpg';

        if (!\file_exists($file)) {
            $file = $this->outputDirectory.'/empty.jpg';
        }

        $response = (new BinaryFileResponse($file, 200, ['Content-Type' => 'image/jpeg']))
            ->setMaxAge('864000')
        ;

        return $response;
    }

    private function handleFormSuccess(MapImageQuery $data, Maps $map): ?Response
    {
        try {
            $image = $this->tilesManager->setMap($map)->createImage($data['ratio'], $data['x'], $data['y'], $data['width'], $data['height'], $data['withImages']);

            $response = (new BinaryFileResponse(
                $image,
                200,
                ['Content-Type' => 'image/jpeg']
            ))
                ->setPublic()
                ->setExpires(new \DateTime('+1 day'))
            ;

            return $response;
        } catch (\Exception $e) {
            $message = '';
            do {
                $message .= ($message ? "\n" : '').$e->getMessage();
            } while ($e = $e->getPrevious());

            return new JsonResponse([
                'error' => true,
                'message' => $message,
            ], 400);
        }
    }
}
