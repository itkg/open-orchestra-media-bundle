<?php

namespace OpenOrchestra\MediaModelBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class OpenOrchestraMediaModelExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('validator.yml');
        $loader->load('listener.yml');
        $loader->load('form.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config['document'] as $class => $content) {
            if (is_array($content)) {
                $container->setParameter('open_orchestra_media.document.' . $class . '.class', $content['class']);
                $definition = new Definition($content['repository'], array($content['class']));
                $definition->setFactory(array(new Reference('doctrine.odm.mongodb.document_manager'), 'getRepository'));
                $definition->addMethodCall('setAggregationQueryBuilder', array(
                    new Reference('doctrine_mongodb.odm.default_aggregation_query')
                ));
                $container->setDefinition('open_orchestra_media.repository.' . $class, $definition);
            }
        }
    }
}
