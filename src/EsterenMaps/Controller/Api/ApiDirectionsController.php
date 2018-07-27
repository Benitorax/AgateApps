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
use EsterenMaps\Entity\Markers;
use EsterenMaps\Repository\TransportTypesRepository;
use EsterenMaps\Services\DirectionsManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route(host="%esteren_domains.api%")
 */
class ApiDirectionsController extends AbstractController
{
    private $versionCode;
    private $versionDate;
    private $transportTypesRepository;
    private $directionsManager;
    private $translator;

    public function __construct(
        string $versionCode,
        string $versionDate,
        TransportTypesRepository $transportTypesRepository,
        DirectionsManager $directionsManager,
        TranslatorInterface $translator
    ) {
        $this->versionCode = $versionCode;
        $this->versionDate = $versionDate;
        $this->transportTypesRepository = $transportTypesRepository;
        $this->directionsManager = $directionsManager;
        $this->translator = $translator;
    }

    /**
     * @Route("/maps/directions/{id}/{from}/{to}",
     *     name="esterenmaps_directions",
     *     requirements={"id" = "\d+", "from" = "\d+", "to" = "\d+"},
     *     methods={"GET"}
     * )
     * @ParamConverter(name="from", class="EsterenMaps\Entity\Markers", options={"id" = "from"})
     * @ParamConverter(name="to", class="EsterenMaps\Entity\Markers", options={"id" = "to"})
     */
    public function getDirectionsAction(Maps $map, Markers $from, Markers $to, Request $request): JsonResponse
    {
        $transportId = $request->query->get('transport');
        $transport = $this->transportTypesRepository->findOneBy(['id' => $transportId]);

        $response = $this->checkResponseCache($map, $from, $to, $request);

        if ($response->isNotModified($request)) {
            return $response;
        }

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

    private function checkResponseCache(Maps $map, Markers $from, Markers $to, Request $request): JsonResponse
    {
        $transportId = $request->query->get('transport');
        $hoursPerDay = $request->query->get('hours_per_day', 7);

        $etag = \sha1(\implode('_', [
            'js',
            $map->getId(),
            $from->getId(),
            $to->getId(),
            $hoursPerDay,
            $transportId,
            $this->versionCode,
        ]));
        $lastModified = new \DateTime($this->versionDate);

        $response = new JsonResponse();
        if (!$request->isNoCache()) {
            $response->setCache([
                'etag' => $etag,
                'last_modified' => $lastModified,
                'max_age' => 600,
                's_maxage' => 600,
                'public' => true,
            ]);
        }

        return $response;
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
