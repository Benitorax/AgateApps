<?php

namespace User\Controller\Admin;

use Admin\Controller\AdminController;
use User\Entity\Subscription;
use User\Entity\User;
use User\Mailer\SubscriptionMailer;

class AdminSubscriptionController extends AdminController
{
    private $mailer;

    public function __construct(SubscriptionMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    protected function persistEntity($subscription)
    {
        if (!$subscription instanceof Subscription) {
            throw new \InvalidArgumentException(sprintf(
                'The %s controller can only manage instances of %s, %s given.',
                __CLASS__, User::class, \is_object($subscription) ? \get_class($subscription) : \gettype($subscription)
            ));
        }

        // Causes the persist + flush
        parent::persistEntity($subscription);

        $this->mailer->sendNewSubscriptionEmail($subscription);
    }
}
