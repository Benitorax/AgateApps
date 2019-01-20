<?php

namespace Tests\CorahnRin\GeneratorTools;

use CorahnRin\Data\DomainsData;
use CorahnRin\Entity\GeoEnvironment;

class GeoEnvironmentStubFactory
{
    public static function rural(): GeoEnvironment
    {
        return new GeoEnvironment(0, '', '', DomainsData::NATURAL_ENVIRONMENT['title']);
    }

    public static function urban(): GeoEnvironment
    {
        return new GeoEnvironment(0, '', '', DomainsData::RELATION['title']);
    }
}

