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

use Doctrine\ORM\EntityManagerInterface;
use EsterenMaps\Api\MarkerApi;
use EsterenMaps\Entity\Markers;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Main\PublicService;

/**
 * @Route(host="%esteren_domains.api%")
 */
class ApiMarkersController implements PublicService
{
    use ApiValidationTrait;

    private $security;
    private $em;
    private $markerApi;

    public function __construct(
        AuthorizationCheckerInterface $security,
        MarkerApi $markerApi,
        EntityManagerInterface $em
    ) {
        $this->security = $security;
        $this->em = $em;
        $this->markerApi = $markerApi;
    }

    /**
     * @Route("/markers", name="maps_api_markers_create", methods={"POST"}, defaults={"_format": "json"})
     */
    public function create(Request $request): Response
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException();
        }

        try {
            $marker = Markers::fromApi($this->markerApi->sanitizeRequestData(json_decode($request->getContent(), true)));

            return $this->handleResponse($this->validate($marker), $marker);
        } catch (HttpException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * @Route("/markers/{id}", name="maps_api_markers_update", methods={"POST"}, defaults={"_format": "json"})
     */
    public function update(Markers $marker, Request $request): Response
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException('Access denied.');
        }

        try {
            $marker->updateFromApi($this->markerApi->sanitizeRequestData(json_decode($request->getContent(), true)));

            return $this->handleResponse($this->validate($marker), $marker);
        } catch (HttpException $e) {
            return $this->handleException($e);
        }
    }

    private function handleResponse(array $messages, Markers $marker): Response
    {
        if (count($messages) > 0) {
            throw new BadRequestHttpException(json_encode($messages, JSON_PRETTY_PRINT));
        }

        $this->em->persist($marker);
        $this->em->flush();

        return new JsonResponse($marker, 200);
    }

    private function handleException(HttpException $exception): Response
    {
        $response = new JsonResponse();
        $response->setStatusCode(400);

        if ($exception instanceof HttpException) {
            $response->setStatusCode($exception->getStatusCode());
        }

        $response->setContent($exception->getMessage());

        return $response;
    }
}
