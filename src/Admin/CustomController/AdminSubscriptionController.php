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

use Admin\Controller\AdminController;
use Subscription\Entity\Subscription;
use Subscription\Mailer\SubscriptionMailer;
use User\Entity\User;

class AdminSubscriptionController extends AdminController
{
    private $mailer;

    public function __construct(SubscriptionMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    protected function persistEntity($subscription): void
    {
        if (!$subscription instanceof Subscription) {
            throw new \InvalidArgumentException(\sprintf(
                'The %s controller can only manage instances of %s, %s given.',
                __CLASS__, User::class, \is_object($subscription) ? \get_class($subscription) : \gettype($subscription)
            ));
        }

        // Causes the persist + flush
        parent::persistEntity($subscription);

        $this->mailer->sendNewSubscriptionEmail($subscription);
    }
}
