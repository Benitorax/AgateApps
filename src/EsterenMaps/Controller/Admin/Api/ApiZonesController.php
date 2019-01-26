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
use EsterenMaps\Api\ZoneApi;
use EsterenMaps\Entity\Zone;
use Main\DependencyInjection\PublicService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ApiZonesController implements PublicService
{
    use ApiValidationTrait;

    private $security;
    private $em;
    private $zoneApi;

    public function __construct(
        AuthorizationCheckerInterface $security,
        ZoneApi $zoneApi,
        EntityManagerInterface $em
    ) {
        $this->security = $security;
        $this->em = $em;
        $this->zoneApi = $zoneApi;
    }

    /**
     * @Route(
     *     "/api/zones",
     *     name="maps_api_zones_create",
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
            $zone = Zone::fromApi($this->zoneApi->sanitizeRequestData(\json_decode($request->getContent(), true)));

            return $this->handleResponse($this->validate($zone), $zone);
        } catch (HttpException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * @Route(
     *     "/api/zones/{id}",
     *     name="maps_api_zones_update",
     *     methods={"POST"},
     *     defaults={"_format" = "json"},
     *     host="%esteren_domains.backoffice%"
     * )
     */
    public function update(Zone $zone, Request $request): Response
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        try {
            $zone->updateFromApi($this->zoneApi->sanitizeRequestData(\json_decode($request->getContent(), true)));

            return $this->handleResponse($this->validate($zone), $zone);
        } catch (HttpException $e) {
            return $this->handleException($e);
        }
    }

    private function handleResponse(array $messages, Zone $zone): Response
    {
        if (\count($messages) > 0) {
            throw new BadRequestHttpException(\json_encode($messages, JSON_PRETTY_PRINT));
        }

        $this->em->persist($zone);
        $this->em->flush();

        return new JsonResponse($zone, 200);
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
