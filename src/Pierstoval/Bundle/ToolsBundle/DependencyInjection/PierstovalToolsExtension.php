<?php

namespace Pierstoval\Bundle\ToolsBundle\DependencyInjection;

use Pierstoval\Bundle\ToolsBundle\Twig\JsonExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @todo Remove this class.
 */
class PierstovalToolsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // compile commonly used classes
        $this->addClassesToCompile([
            JsonExtension::class,
        ]);
    }
}
