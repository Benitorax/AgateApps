<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace User\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use User\Entity\User;
use User\Util\CanonicalizerTrait;

class RegistrationFormType extends AbstractType
{
    use CanonicalizerTrait;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $canonicalizer = \Closure::fromCallable([$this, 'canonicalize']);

        $builder
            ->add('email', EmailType::class, [
                'label' => 'form.email',
                'translation_domain' => 'user',
            ])
            ->add('username', null, [
                'label' => 'form.username',
                'translation_domain' => 'user',
            ])
            ->add('plainPassword', PasswordType::class, [
                'translation_domain' => 'user',
                'label' => 'form.password',
                'invalid_message' => 'user.password.mismatch',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($canonicalizer) {
                /** @var User $user */
                $user = $event->getForm()->getData();
                $user->setUsernameCanonical($canonicalizer($user->getUsername()));
                $user->setEmailCanonical($canonicalizer($user->getEmail()));
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
