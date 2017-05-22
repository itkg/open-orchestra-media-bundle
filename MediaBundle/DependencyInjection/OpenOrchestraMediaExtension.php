<?php

namespace OpenOrchestra\MediaBundle\DependencyInjection;

use OpenOrchestra\Media\DisplayBlock\Strategies\DisplayMediaStrategy;
use OpenOrchestra\Media\DisplayBlock\Strategies\SlideshowStrategy;
use OpenOrchestra\Media\DisplayBlock\Strategies\GalleryStrategy;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class OpenOrchestraMediaExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('open_orchestra_media.media_domain', $config['media_domain']);
        $container->setParameter('open_orchestra_media.media_storage_directory', $config['media_storage_directory']);
        $container->setParameter('open_orchestra_media.allowed_mime_type', $config['allowed_mime_type']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('bbcode.yml');
        $loader->load('display.yml');
        $loader->load('twig.yml');
        $loader->load('manager.yml');

        if (array_key_exists("OpenOrchestraDisplayBundle", $container->getParameter('kernel.bundles'))) {
            $this->updateBlockParameter($container);
            $loader->load('display_block.yml');
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function updateBlockParameter(ContainerBuilder $container)
    {
        $blockType = array(
            GalleryStrategy::NAME,
            SlideshowStrategy::NAME,
            DisplayMediaStrategy::NAME,
        );

        $blocksAlreadySet = array();
        if ($container->hasParameter('open_orchestra.blocks')) {
            $blocksAlreadySet = $container->getParameter('open_orchestra.blocks');
        }
        $blocks = array_merge($blocksAlreadySet, $blockType);
        $container->setParameter('open_orchestra.blocks', $blocks);
    }
}
