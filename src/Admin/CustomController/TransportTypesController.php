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

namespace Admin\CustomController;

use EsterenMaps\Entity\RouteType;
use EsterenMaps\Entity\TransportModifier;
use EsterenMaps\Entity\TransportType;
use Symfony\Component\Form\FormBuilder;

class TransportTypesController extends BaseMapAdminController
{
    /**
     * Creates the form builder of the form used to create or edit the given entity.
     *
     * @param string $view The name of the view where this form is used ('new' or 'edit')
     *
     * @return FormBuilder
     */
    protected function createTransportTypesEntityFormBuilder(TransportType $entity, $view)
    {
        // Get IDs in the entity and try to retrieve non-existing transport ids.
        $routesTypesIds = \array_reduce(
            $entity->getTransportsModifiers()->toArray(),
            function (array $carry, TransportModifier $routeTransport) {
                $carry[] = $routeTransport->getRouteType()->getId();

                return $carry;
            },
            []
        );

        $missingRoutesTypes = $this->em
            ->getRepository(RouteType::class)
            ->findNotInIds($routesTypesIds)
        ;

        foreach ($missingRoutesTypes as $routeType) {
            $entity->addTransportsModifier(
                (new TransportModifier())
                    ->setTransportType($entity)
                    ->setRouteType($routeType)
            );
        }

        return parent::createEntityFormBuilder($entity, $view);
    }
}
