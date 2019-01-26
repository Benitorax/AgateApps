<?php

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
use EsterenMaps\Api\RouteApi;
use EsterenMaps\Entity\Route as RouteEntity;
use Main\DependencyInjection\PublicService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ApiRoutesController implements PublicService
{
    use ApiValidationTrait;

    private $security;
    private $em;
    private $routeApi;

    public function __construct(
        AuthorizationCheckerInterface $security,
        RouteApi $routeApi,
        EntityManagerInterface $em
    ) {
        $this->security = $security;
        $this->em = $em;
        $this->routeApi = $routeApi;
    }

    /**
     * @Route(
     *     "/api/routes",
     *     name="maps_api_routes_create",
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
            $route = RouteEntity::fromApi($this->routeApi->sanitizeRequestData(\json_decode($request->getContent(), true)));

            return $this->handleResponse($this->validate($route), $route);
        } catch (HttpException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * @Route(
     *     "/api/routes/{id}",
     *     name="maps_api_routes_update",
     *     methods={"POST"},
     *     defaults={"_format" = "json"},
     *     host="%esteren_domains.backoffice%"
     * )
     */
    public function update(RouteEntity $route, Request $request): Response
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        try {
            $route->updateFromApi($this->routeApi->sanitizeRequestData(\json_decode($request->getContent(), true)));

            return $this->handleResponse($this->validate($route), $route);
        } catch (HttpException $e) {
            return $this->handleException($e);
        }
    }

    private function handleResponse(array $messages, RouteEntity $route): Response
    {
        if (\count($messages) > 0) {
            throw new BadRequestHttpException(\json_encode($messages, JSON_PRETTY_PRINT));
        }

        $this->em->persist($route);
        $this->em->flush();

        return new JsonResponse($route, 200);
    }

    private function handleException(HttpException $exception): Response
    {
        $response = new JsonResponse();
        $response->setStatusCode(400);

        $response->setStatusCode($exception->getStatusCode());

        $response->setContent($exception->getMessage());

        return $response;
    }
}
