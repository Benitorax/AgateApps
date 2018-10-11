<?php

namespace User\Controller\Admin;

use Admin\Controller\AdminController;
use User\Entity\Subscription;
use User\Entity\User;

class AdminSubscriptionController extends AdminController
{
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
    }
}
