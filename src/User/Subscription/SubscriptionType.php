<?php

namespace User\Subscription;

final class SubscriptionType
{
    public const TYPE_TRAVELS = 'subscription.travels';

    public const TYPES = [
        self::TYPE_TRAVELS => self::TYPE_TRAVELS,
    ];

    public const TYPES_ROLES = [
        self::TYPE_TRAVELS => ['ROLE_BACKER_TRAVELS'],
    ];
}
