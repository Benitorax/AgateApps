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

use EsterenMaps\Entity\Map;
use EsterenMaps\Entity\Markers;
use EsterenMaps\Repository\TransportTypesRepository;
use EsterenMaps\Services\DirectionsManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class ApiDirectionsController extends AbstractController
{
    private $transportTypesRepository;
    private $directionsManager;
    private $translator;
    /**
     * @var array
     */
    private $mapsAcceptableHosts;

    public function __construct(
        TransportTypesRepository $transportTypesRepository,
        DirectionsManager $directionsManager,
        TranslatorInterface $translator,
        array  $mapsAcceptableHosts
    ) {
        $this->transportTypesRepository = $transportTypesRepository;
        $this->directionsManager = $directionsManager;
        $this->translator = $translator;
        $this->mapsAcceptableHosts = $mapsAcceptableHosts;
    }

    /**
     * @Route("/api/maps/directions/{id}/{from}/{to}",
     *     name="esterenmaps_directions",
     *     requirements={"id" = "\d+", "from" = "\d+", "to" = "\d+"},
     *     methods={"GET"}
     * )
     * @ParamConverter(name="from", class="EsterenMaps\Entity\Markers", options={"id" = "from"})
     * @ParamConverter(name="to", class="EsterenMaps\Entity\Markers", options={"id" = "to"})
     */
    public function __invoke(Map $map, Markers $from, Markers $to, Request $request): JsonResponse
    {
        if (!\in_array($request->getHost(), $this->mapsAcceptableHosts, true)) {
            throw new NotFoundHttpException();
        }

        if (!$this->isGranted(['ROLE_MAPS_VIEW', 'SUBSCRIBED_TO_MAPS_VIEW', 'ROLE_ADMIN'])) {
            throw $this->createAccessDeniedException();
        }

        $transportId = $request->query->get('transport');
        $transport = $this->transportTypesRepository->findOneBy(['id' => $transportId]);

        $response = new JsonResponse();
        $response->setCache([
            'max_age' => 600,
            's_maxage' => 3600,
            'public' => true,
        ]);

        if (!$transport && $transportId) {
            $output = $this->getError($from, $to, $transportId, 'Transport not found.');
            $response->setStatusCode(404);
        } else {
            $output = $this->directionsManager->getDirections($map, $from, $to, $request->query->get('hours_per_day', 7), $transport);
            if (0 === \count($output)) {
                $output = $this->getError($from, $to);
                $response->setStatusCode(404);
            }
        }

        return $response->setData($output);
    }

    private function getError(Markers $from, Markers $to, int $transportId = null, string $message = 'No path found for this query.'): array
    {
        return [
            'error' => true,
            'message' => $this->translator->trans($message),
            'query' => [
                'from' => $from,
                'to' => $to,
                'transport' => $transportId,
            ],
        ];
    }
}
