<?php

declare(strict_types=1);

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Voucher\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RedeemVoucherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('voucher_code', TextType::class, [
                'label' => 'voucher.redeem.code_label',
            ])
            ->add('confirmation', SubmitType::class, [
                'label' => 'voucher.redeem.confirmation_button',
            ])
            ->add('activate', SubmitType::class, [
                'label' => 'voucher.redeem.submit',
                'attr' => ['class' => 'btn'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('show_confirmation', false);
        $resolver->setAllowedTypes('show_confirmation', ['bool']);
    }
}
