<?php

namespace OpenOrchestra\Media\DisplayMedia\Strategies;

use OpenOrchestra\Media\Model\MediaInterface;

/**
 * Class DefaultStrategy
 */
class DefaultStrategy extends AbstractStrategy
{
    /**
     * @param MediaInterface $media
     *
     * @return bool
     */
    public function support(MediaInterface $media)
    {
        return true;
    }

    /**
     * @deprecated displayMedia is deprecated since version 1.2.0 and will be removed in 2.0.0 use renderMedia
     *
     * @param MediaInterface $media
     * @param string         $format
     * @param string         $style
     *
     * @return String
     */
    public function displayMedia(MediaInterface $media, $format = '', $style = '')
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.2.0 and will be removed in 2.0.0.'
            . 'Use the '.__CLASS__.'::renderMedia method instead.', E_USER_DEPRECATED);

        return $this->render(
            'OpenOrchestraMediaBundle:RenderMedia:default.html.twig',
            array(
                'media_url' => $this->getFileUrl($media->getFilesystemName()),
                'media_name' => $media->getName(),
                'id' => '',
                'class' => '',
                'style' => $style
            )
        );
    }

    /**
     * @param MediaInterface $media
     * @param array          $options
     *
     * @return String
     */
    public function renderMedia(MediaInterface $media, array $options)
    {
        $options = $this->validateOptions($options, __METHOD__);

        return $this->render(
            'OpenOrchestraMediaBundle:RenderMedia:default.html.twig',
            array(
                'media_url' => $this->getFileUrl($media->getFilesystemName()),
                'media_name' => $media->getName(),
                'id' => $options['id'],
                'class' => $options['class'],
                'style' => $options['style']
            )
        );
    }

    /**
     * @param MediaInterface $media
     * @param string         $format
     *
     * @return string
     */
    public function getMediaFormatUrl(MediaInterface $media, $format)
    {
        return $this->displayPreview($media);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'default';
    }
}
