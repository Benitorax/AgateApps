<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Admin\CustomController;

use EsterenMaps\Entity\RoutesTypes;
use EsterenMaps\Entity\TransportModifiers;
use EsterenMaps\Entity\TransportTypes;
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
    protected function createTransportTypesEntityFormBuilder(TransportTypes $entity, $view)
    {
        // Get IDs in the entity and try to retrieve non-existing transport ids.
        $routesTypesIds = \array_reduce(
            $entity->getTransportsModifiers()->toArray(),
            function (array $carry, TransportModifiers $routeTransport) {
                $carry[] = $routeTransport->getRouteType()->getId();

                return $carry;
            },
            []
        );

        $missingRoutesTypes = $this->em
            ->getRepository(RoutesTypes::class)
            ->findNotInIds($routesTypesIds)
        ;

        foreach ($missingRoutesTypes as $routeType) {
            $entity->addTransportsModifier(
                (new TransportModifiers())
                    ->setTransportType($entity)
                    ->setRouteType($routeType)
            );
        }

        return parent::createEntityFormBuilder($entity, $view);
    }
}
