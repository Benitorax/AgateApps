<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\Form;

use EsterenMaps\Entity\Faction;
use EsterenMaps\Entity\Markers;
use EsterenMaps\Entity\Routes;
use EsterenMaps\Entity\RoutesTypes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class ApiRouteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new Constraints\NotBlank(),
                ],
            ])
            ->add('forcedDistance', NumberType::class, [
                'constraints' => [
                    new Constraints\Range(['min' => 0]),
                ],
            ])
            ->add('guarded', CheckboxType::class, [
                'constraints' => [
                    new Constraints\Type(['type' => 'bool']),
                ],
            ])
            ->add('routeType', EntityType::class, [
                'class' => RoutesTypes::class,
            ])
            ->add('markerStart', EntityType::class, [
                'class' => Markers::class,
            ])
            ->add('markerEnd', EntityType::class, [
                'class' => Markers::class,
            ])
            ->add('faction', EntityType::class, [
                'class' => Faction::class,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('data_class', Routes::class)
        ;
    }
}
