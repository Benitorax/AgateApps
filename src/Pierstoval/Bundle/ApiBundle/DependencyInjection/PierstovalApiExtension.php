<?php

namespace Pierstoval\Bundle\ApiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PierstovalApiExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        if (isset($config['services'])) {
            foreach ($config['services'] as $name => $v) {
                $config['services'][$name]['name'] = $name;
            }
        }

        // Remove duplicates in case of multiple configurations
        $config['allowed_origins'] = array_unique($config['allowed_origins']);

        foreach ($config as $name => $value) {
            $container->setParameter('pierstoval_api.'.$name, $value);
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
