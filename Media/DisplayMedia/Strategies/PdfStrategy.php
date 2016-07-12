<?php

namespace OpenOrchestra\Media\DisplayMedia\Strategies;

use OpenOrchestra\Media\Model\MediaInterface;

/**
 * Class PdfStrategy
 */
class PdfStrategy extends AbstractStrategy
{
    const MEDIA_TYPE = 'pdf';

    /**
     * @param MediaInterface $media
     *
     * @return bool
     */
    public function support(MediaInterface $media)
    {
        return self::MEDIA_TYPE == $media->getMediaType();
    }

    /**
     * @deprecated displayMedia is deprecated since version 1.2.0 and will be removed in 2.0.0 use renderMedia
     *
     * @param MediaInterface $media
     * @param string         $format
     * @param string         $style
     *
     * @return string
     */
    public function displayMedia(MediaInterface $media, $format = '', $style = '')
    {
        return $this->render(
            'OpenOrchestraMediaBundle:DisplayMedia/FullDisplay:pdf.html.twig',
            array(
                'media_url' => $this->getFileUrl($media->getFilesystemName()),
                'media_name' => $media->getName(),
                'id' => '',
                'class' => '',
                'style' => $style,
            )
        );
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
            'OpenOrchestraMediaBundle:DisplayMedia/FullDisplay:pdf.html.twig',
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
        return 'pdf';
    }
}
