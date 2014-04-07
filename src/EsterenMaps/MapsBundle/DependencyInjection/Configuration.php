<?php

namespace EsterenMaps\MapsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('esterenmaps');

        $rootNode
            ->children()
                ->scalarNode('tile_size')
                    ->defaultValue(168)
                    ->info('La largeur et la hauteur des tuiles générées par l\'application Maps')
                    ->example('168')
                ->end()
            ->end()
        ;
        return $treeBuilder;
    }
}