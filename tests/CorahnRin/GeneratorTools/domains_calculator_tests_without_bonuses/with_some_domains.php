<?php

use CorahnRin\Data\DomainsData;
use Tests\CorahnRin\GeneratorTools\GeoEnvironmentStubFactory as GeoEnv;

return [
    'calculator_arguments' => [
        [
            DomainsData::NATURAL_ENVIRONMENT['title'],
            DomainsData::PERCEPTION['title'],
        ],
        'domains.close_combat', // Ost service
        GeoEnv::rural(),
        [
            'domains.close_combat' => 2,
            'domains.stealth' => 1,
            'domains.magience' => 1,
            'domains.natural_environment' => 2,
            'domains.occultism' => 3,
            'domains.perception' => 3,
        ],
    ],

    'expected_values' => [
        'domains.craft' => 0,
        'domains.close_combat' => 3,
        'domains.stealth' => 1,
        'domains.magience' => 1,
        'domains.natural_environment' => 4,
        'domains.demorthen_mysteries' => 0,
        'domains.occultism' => 3,
        'domains.perception' => 4,
        'domains.prayer' => 0,
        'domains.feats' => 0,
        'domains.relation' => 0,
        'domains.performance' => 0,
        'domains.science' => 0,
        'domains.shooting_and_throwing' => 0,
        'domains.travel' => 0,
        'domains.erudition' => 0,
    ],
];
