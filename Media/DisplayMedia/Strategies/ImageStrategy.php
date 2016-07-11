<?php

namespace OpenOrchestra\Media\DisplayMedia\Strategies;

use OpenOrchestra\Media\Model\MediaInterface;

/**
 * Class ImageStrategy
 */
class ImageStrategy extends AbstractStrategy
{
    /**
     * @param MediaInterface $media
     *
     * @return bool
     */
    public function support(MediaInterface $media)
    {
        return strpos($media->getMimeType(), 'image') === 0;
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
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.2.0 and will be removed in 2.0.0.'
            . 'Use the '.__CLASS__.'::renderMedia method instead.', E_USER_DEPRECATED);

        $request = $this->requestStack->getMasterRequest();

        return $this->render(
            'OpenOrchestraMediaBundle:DisplayMedia/FullDisplay:image.html.twig',
            array(
                'media_url' => $this->getMediaFormatUrl($media, $format),
                'media_alt' => $media->getAlt($request->getLocale()),
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
     * @return string
     */
    public function renderMedia(MediaInterface $media, array $options)
    {
        $options = $this->validateOptions($options, __METHOD__);

        $request = $this->requestStack->getMasterRequest();

        return $this->render(
            'OpenOrchestraMediaBundle:DisplayMedia/FullDisplay:image.html.twig',
            array(
                'media_url' => $this->getMediaFormatUrl($media, $options['format']),
                'media_alt' => $media->getAlt($request->getLocale()),
                'id' => $options['id'],
                'class' => $options['class'],
                'style' => $options['style']
            )
        );
    }

    /**
     * @param MediaInterface $media
     *
     * @param MediaInterface $media
     * @param string         $format
     * @param string         $style
     *
     * @return string
     */
    public function displayMediaForWysiwyg(MediaInterface $media, $format = '', $style = '')
    {
        $request = $this->requestStack->getMasterRequest();

        return $this->render(
            'OpenOrchestraMediaBundle:BBcode/WysiwygDisplay:image.html.twig',
            array(
                'media_url' => $this->getMediaFormatUrl($media, $format),
                'media_alt' => $media->getAlt($request->getLocale()),
                'media_id' => $media->getId(),
                'media_format' => $format,
                'style' => $style,
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
        $key = $media->getFilesystemName();

        if ($format != '' && MediaInterface::MEDIA_ORIGINAL != $format) {
            $key = $media->getAlternative($format);
        }

        return $this->getFileUrl($key);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return "image";
    }
}
