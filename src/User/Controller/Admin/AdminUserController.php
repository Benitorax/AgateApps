<?php

namespace User\Controller\Admin;

use Admin\Controller\AdminController;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use User\Entity\User;
use User\Mailer\UserMailer;
use User\Util\CanonicalizerTrait;
use User\Util\TokenGeneratorTrait;

class AdminUserController extends AdminController
{
    use CanonicalizerTrait;
    use TokenGeneratorTrait;

    private $passwordEncoder;
    private $mailer;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        UserMailer $mailer
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
    }

    protected function initialize(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', 'Only super admins can manage users.');
        return parent::initialize($request);
    }


    protected function createEntityFormBuilder($entity, $view)
    {
        $canonicalizer = \Closure::fromCallable([$this, 'canonicalize']);

        $builder = parent::createEntityFormBuilder($entity, $view);

        $builder
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($canonicalizer) {
                /** @var User $user */
                $user = $event->getForm()->getData();
                $user->setUsernameCanonical($canonicalizer((string) $user->getUsername()));
                $user->setEmailCanonical($canonicalizer((string) $user->getEmail()));
            })
        ;

        return $builder;
    }

    protected function persistEntity($user)
    {
        if (!$user instanceof User) {
            throw new \InvalidArgumentException(sprintf(
                'The %s controller can only manage instances of %s, %s given.',
                __CLASS__, User::class, \is_object($user) ? \get_class($user) : \gettype($user)
            ));
        }

        if (!$hasPassword = $user->getPlainPassword()) {
            $user->setConfirmationToken($this->generateToken());
            $user->setPlainPassword(\uniqid('', true));
        }
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
        $user->setEmailConfirmed(true);
        $user->eraseCredentials();

        // Causes the persist + flush
        parent::persistEntity($user);

        if (!$hasPassword) {
            // With no password, we send a "reset password" email to the user
            $this->mailer->sendResettingEmailMessage($user);
        }

    }
}
