<?php

namespace User\Subscription;

final class SubscriptionType
{
    public const ESTEREN_MAPS = 'subscription.esteren_maps';

    public const TYPES = [
        self::ESTEREN_MAPS => self::ESTEREN_MAPS,
    ];

    public const TYPES_ROLES = [
        self::ESTEREN_MAPS => ['ROLE_MAPS_VIEW'],
    ];
}
