<?php

namespace OpenOrchestra\Media\DisplayMedia\Strategies;

use OpenOrchestra\Media\Model\MediaInterface;

/**
 * Class PdfStrategy
 */
class PdfStrategy extends AbstractStrategy
{
    const MIME_TYPE_PDF = 'application/pdf';

    /**
     * @param MediaInterface $media
     *
     * @return bool
     */
    public function support(MediaInterface $media)
    {
        return self::MIME_TYPE_PDF == $media->getMimeType();
    }

   /**
     * @param MediaInterface $media
     * @param string         $format
     *
     * @return String
     */
    public function displayMedia(MediaInterface $media, $format = '')
    {
        return '<img src="' . $this->getFileUrl($media->getFilesystemName()) . '" alt="' . $media->getAlt($this->request->getLocale()) . '" />';
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
