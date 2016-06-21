<?php

namespace Tests\DependencyInjection;

use Tests\WebTestCase;

class AppContainerTest extends WebTestCase
{

    public function provideServiceIds()
    {
        return [
            'corahnrin_generator.pdf_manager'     => ['corahnrin_generator.pdf_manager'],
            'corahnrin_generator.steps.step_01'   => ['corahnrin_generator.steps.step_01'],
            'esterenmaps'                         => ['esterenmaps'],
            'esterenmaps.tiles_manager'           => ['esterenmaps.tiles_manager'],
            'esterenmaps.map_image_manager'       => ['esterenmaps.map_image_manager'],
            'esterenmaps.directions_manager'      => ['esterenmaps.directions_manager'],
            'esterenmaps.coordinates_manager'     => ['esterenmaps.coordinates_manager'],
            'esterenmaps.subscriber.cache_clear'  => ['esterenmaps.subscriber.cache_clear'],
            'pierstoval.api.listener'             => ['pierstoval.api.listener'],
            'pierstoval.api.origin_checker'       => ['pierstoval.api.origin_checker'],
            'pierstoval_tools.twig.json'          => ['pierstoval_tools.twig.json'],
        ];
    }

    /**
     * @dataProvider provideServiceIds
     *
     * @param string $serviceId
     */
    public function testContainer($serviceId)
    {
        $container = $this->getClient()->getContainer();

        // We "fail" manually to avoid phpunit to say there's an error.
        if (!$container->has($serviceId)) {
            static::fail('Service '.$serviceId.' does not exist.');

            return;
        }

        $service = $container->get($serviceId);
        static::assertNotNull($service);
    }

}
