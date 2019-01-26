<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace User\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use User\ConnectApi\UluleClient;
use User\Entity\User;

class UluleConnectType extends AbstractType
{
    private $ululeClient;
    private $translator;

    public function __construct(UluleClient $ululeClient, TranslatorInterface $translator)
    {
        $this->ululeClient = $ululeClient;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */
        $user = $builder->getData();

        $builder
            ->add('ululeUsername', $user->getUluleId() ? HiddenType::class : TextType::class, [
                'label' => 'form.ulule_username',
                'translation_domain' => 'user',
            ])
            ->add('ululeApiToken', $user->getUluleId() ? HiddenType::class : PasswordType::class, [
                'label' => 'form.ulule_api_token',
                'translation_domain' => 'user',
            ])
            ->add('disconnectUlule', $user->getUluleId() ? CheckboxType::class : HiddenType::class, [
                'label' => 'form.ulule_disconnect',
                'translation_domain' => 'user',
                'mapped' => false,
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                // Manage Ulule Connect
                $form = $event->getForm();

                /** @var User $user */
                $user = $form->getData();

                $disconnectUlule = $form->get('disconnectUlule')->getData();

                if (true === $disconnectUlule) {
                    $user->setUluleApiToken(null);
                    $user->setUluleUsername(null);
                    $user->setUluleId(null);

                    return;
                }

                $ululeUsername = $form->get('ululeUsername')->getData();

                if ($ululeUsername) {
                    $tokenField = $form->get('ululeApiToken');
                    $ululeToken = $tokenField->getData() ?: '';

                    if (!$ululeToken) {
                        $error = new FormError($this->translator->trans('form.error.need_api_token', [], 'user'));
                        $tokenField->addError($error);

                        return;
                    }

                    $ululeUser = $this->ululeClient->basicApiTokenConnect($ululeUsername, $ululeToken);

                    if (!$ululeUser) {
                        $error = new FormError($this->translator->trans('form.error.ulule_credentials_invalid', [], 'user'));
                        $tokenField->addError($error);

                        return;
                    }

                    $user->setUluleId($ululeUser->getId());
                    $user->setUluleUsername($ululeUsername);
                    $user->setUluleApiToken($ululeToken);
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
