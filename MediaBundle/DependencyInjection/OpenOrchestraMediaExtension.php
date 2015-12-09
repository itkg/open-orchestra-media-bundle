<?php

namespace OpenOrchestra\MediaBundle\DependencyInjection;

use OpenOrchestra\Media\DisplayBlock\Strategies\DisplayMediaStrategy;
use OpenOrchestra\Media\DisplayBlock\Strategies\SlideshowStrategy;
use OpenOrchestra\Media\DisplayBlock\Strategies\GalleryStrategy;
use OpenOrchestra\Media\DisplayBlock\Strategies\MediaListByKeywordStrategy;
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

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('bbcode.yml');
        $loader->load('display.yml');
        $loader->load('twig.yml');

        if (array_key_exists("OpenOrchestraDisplayBundle", $container->getParameter('kernel.bundles'))) {
            $this->updateBlockParameter($container);
            $loader->load('display_block.yml');
        }

        if ($container->hasParameter('assetic.bundles')) {
            $asseticBundles = $container->getParameter('assetic.bundles');
            $asseticBundles[] = 'OpenOrchestraMediaBundle';
            $container->setParameter('assetic.bundles', $asseticBundles);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function updateBlockParameter(ContainerBuilder $container)
    {
        $blockType = array(
            GalleryStrategy::GALLERY,
            SlideshowStrategy::SLIDESHOW,
            MediaListByKeywordStrategy::MEDIA_LIST_BY_KEYWORD,
            DisplayMediaStrategy::DISPLAY_MEDIA,
        );

        $blocksAlreadySet = array();
        if ($container->hasParameter('open_orchestra.blocks')) {
            $blocksAlreadySet = $container->getParameter('open_orchestra.blocks');
        }
        $blocks = array_merge($blocksAlreadySet, $blockType);
        $container->setParameter('open_orchestra.blocks', $blocks);
    }
}
