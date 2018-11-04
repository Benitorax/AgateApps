<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Agate\Form;

use Agate\EventListener\CaptchaFormSubscriber;
use Agate\Model\ContactMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class ContactType extends AbstractType
{
    private $kernelEnvironment;
    private $captchaFormSubscriber;

    public function __construct(string $kernelEnvironment, CaptchaFormSubscriber $captchaFormSubscriber)
    {
        $this->kernelEnvironment = $kernelEnvironment;
        $this->captchaFormSubscriber = $captchaFormSubscriber;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->captchaFormSubscriber->setRequest($options['request']);

        $builder
            ->add('name', TextType::class, [
                'label' => 'contact.form.name',
                'attr' => [
                    'pattern' => '.{2,}',
                ],
                'constraints' => [
                    new Constraints\NotBlank(),
                    new Constraints\Length(['min' => 2]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'contact.form.email',
                'constraints' => [
                    new Constraints\NotBlank(),
                    new Constraints\Email([
                        'checkHost' => 'prod' === $this->kernelEnvironment,
                        'checkMX' => 'prod' === $this->kernelEnvironment,
                    ]),
                ],
            ])
            ->add('subject', ChoiceType::class, [
                'label' => 'contact.form.subject',
                'choices' => ContactMessage::SUBJECTS,
                'constraints' => [
                    new Constraints\Choice(['choices' => ContactMessage::SUBJECTS]),
                ],
            ])
            ->add('productRange', ChoiceType::class, [
                'label' => 'contact.form.product_range',
                'choices' => ContactMessage::PRODUCT_RANGES,
                'required' => false,
                'constraints' => [
                    new Constraints\Choice(['choices' => ContactMessage::PRODUCT_RANGES]),
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'contact.form.title',
                'constraints' => [
                    new Constraints\NotBlank(),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'contact.form.message',
                'constraints' => [
                    new Constraints\NotBlank(),
                ],
            ])
            ->addEventSubscriber($this->captchaFormSubscriber)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'translation_domain' => 'agate',
                'data_class' => ContactMessage::class,
            ])
            ->setRequired('request')
            ->setAllowedTypes('request', Request::class)
        ;
    }
}
