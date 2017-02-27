<?php

namespace OpenOrchestra\Media\DisplayMedia\Strategies;

use OpenOrchestra\Media\Model\MediaInterface;

/**
 * Class AudioStrategy
 */
class AudioStrategy extends AbstractStrategy
{
    const MEDIA_TYPE = 'audio';

    /**
     * @param MediaInterface $media
     *
     * @return bool
     */
    public function support(MediaInterface $media)
    {
        return self::MEDIA_TYPE === $media->getMediaType();
    }

    /**
     * @param MediaInterface $media
     * @param array          $options
     *
     * @return string
     */
    public function renderMedia(MediaInterface $media, array $options)
    {
        $options = $this->validateOptions($options, __METHOD__);

        return $this->render(
            'OpenOrchestraMediaBundle:RenderMedia:audio.html.twig',
            array(
                'media_url' => $this->getFileUrl($media->getFilesystemName()),
                'media_type' => $media->getMimeType(),
                'id' => $options['id'],
                'class' => $options['class'],
                'style' => $options['style'],
            )
        );
    }

    /**
     * @param MediaInterface $media
     *
     * @param MediaInterface $media
     * @param string         $format
     * @param string         $alt
     * @param string         $legend
     *
     * @return string
     */
    public function displayMediaForWysiwyg(MediaInterface $media, $format = '', $alt = '', $legend = '')
    {
        return $this->render(
            'OpenOrchestraMediaBundle:BBcode/WysiwygDisplay:audio.html.twig',
            array(
                'media_id' => $media->getId()
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
        return 'audio';
    }
}
