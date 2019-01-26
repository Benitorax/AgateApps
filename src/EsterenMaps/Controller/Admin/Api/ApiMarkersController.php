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

namespace EsterenMaps\Controller\Admin\Api;

use Doctrine\ORM\EntityManagerInterface;
use EsterenMaps\Api\MarkerApi;
use EsterenMaps\Entity\Marker;
use Main\DependencyInjection\PublicService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
     * @Route(
     *     "/api/markers",
     *     name="maps_api_markers_create",
     *     methods={"POST"},
     *     defaults={"_format" = "json"},
     *     host="%esteren_domains.backoffice%"
     * )
     */
    public function create(Request $request): Response
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        try {
            $marker = Marker::fromApi($this->markerApi->sanitizeRequestData(\json_decode($request->getContent(), true)));

            return $this->handleResponse($this->validate($marker), $marker);
        } catch (HttpException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * @Route(
     *     "/api/markers/{id}",
     *     name="maps_api_markers_update",
     *     methods={"POST"},
     *     defaults={"_format" = "json"},
     *     host="%esteren_domains.backoffice%"
     * )
     */
    public function update(Marker $marker, Request $request): Response
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        try {
            $marker->updateFromApi($this->markerApi->sanitizeRequestData(\json_decode($request->getContent(), true)));

            return $this->handleResponse($this->validate($marker), $marker);
        } catch (HttpException $e) {
            return $this->handleException($e);
        }
    }

    private function handleResponse(array $messages, Marker $marker): Response
    {
        if (\count($messages) > 0) {
            throw new BadRequestHttpException(\json_encode($messages, JSON_PRETTY_PRINT));
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
