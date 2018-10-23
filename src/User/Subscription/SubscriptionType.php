<?php

namespace User\Subscription;

final class SubscriptionType
{
    public const ESTEREN_MAPS = 'subscription.esteren_maps';

    public const TYPES = [
        self::ESTEREN_MAPS => self::ESTEREN_MAPS,
    ];

    public const TYPES_PERMISSIONS = [
        self::ESTEREN_MAPS => ['SUBSCRIBED_TO_MAPS_VIEW'],
    ];
}
