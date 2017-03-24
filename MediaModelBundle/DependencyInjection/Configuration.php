<?php

namespace OpenOrchestra\MediaModelBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('open_orchestra_media_model');

        $rootNode->children()
            ->arrayNode('document')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('media')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->defaultValue('OpenOrchestra\MediaModelBundle\Document\Media')->end()
                            ->scalarNode('repository')->defaultValue('OpenOrchestra\MediaModelBundle\Repository\MediaRepository')->end()
                        ->end()
                    ->end()
                    ->arrayNode('media_folder')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->defaultValue('OpenOrchestra\MediaModelBundle\Document\MediaFolder')->end()
                            ->scalarNode('repository')->defaultValue('OpenOrchestra\MediaModelBundle\Repository\FolderRepository')->end()
                        ->end()
                    ->end()
                    ->arrayNode('media_library_sharing')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->defaultValue('OpenOrchestra\MediaModelBundle\Document\MediaLibrarySharing')->end()
                            ->scalarNode('repository')->defaultValue('OpenOrchestra\MediaModelBundle\Repository\MediaLibrarySharingRepository')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
