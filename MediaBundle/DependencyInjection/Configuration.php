<?php

namespace OpenOrchestra\MediaBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('open_orchestra_media');

        $rootNode->children()
            ->scalarNode('media_domain')->defaultValue('')->end()
            ->scalarNode('tmp_dir')->defaultValue('/tmp')->end()
            ->scalarNode('filesystem')->defaultValue('media_storage')->end()
            ->scalarNode('compression_quality')->defaultValue(75)->end()
            ->arrayNode('thumbnail')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('max_height')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('max_height')->defaultValue(100)->end()
                        ->end()
                    ->end()
                    ->arrayNode('max_width')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('max_width')->defaultValue(100)->end()
                        ->end()
                    ->end()
                    ->arrayNode('rectangle')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('max_width')->defaultValue(100)->end()
                            ->scalarNode('max_height')->defaultValue(70)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
