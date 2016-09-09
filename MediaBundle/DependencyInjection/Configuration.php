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
            ->arrayNode('allowed_mime_type')
            ->info('List of allowed mime type')
            ->prototype('scalar')->end()
            ->defaultValue( array(
                'image/jpeg',
                'image/png',
                'image/gif',
                'audio/mpeg',
                'video/mpeg',
                'video/mp4',
                'video/quicktime',
                'video/x-ms-wmv',
                'video/x-msvideo',
                'video/x-flv',
                'video/webm',
                'application/pdf',
            ))
            ->end()
        ->end();

        return $treeBuilder;
    }
}
