<?php

namespace Tests\CorahnRin\GeneratorTools;

use CorahnRin\Data\DomainsData;
use CorahnRin\Entity\GeoEnvironment;

class GeoEnvironmentStub extends GeoEnvironment
{
    private function __construct()
    {
        // Just don't call native constructor
    }

    public static function rural(): self
    {
        $stub = new self();

        $stub->domain = DomainsData::NATURAL_ENVIRONMENT['title'];

        return $stub;
    }

    public static function urban(): self
    {
        $stub = new self();

        $stub->domain = DomainsData::RELATION['title'];

        return $stub;
    }
}

