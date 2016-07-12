<?php

namespace OpenOrchestra\Media\DisplayMedia\Strategies;

use OpenOrchestra\Media\Model\MediaInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class VideoStrategy
 */
class VideoStrategy extends AbstractStrategy
{
    const MEDIA_TYPE = 'video';

    /**
     * @param RequestStack $requestStack
     * @param string       $mediaDomain
     */
    public function __construct(RequestStack $requestStack, $mediaDomain = "")
    {
        parent::__construct($requestStack, $mediaDomain);

        $this->validOptions[] = 'width';
        $this->validOptions[] = 'height';
    }

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
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.2.0 and will be removed in 2.0.0.'
            . 'Use the '.__CLASS__.'::renderMedia method instead.', E_USER_DEPRECATED);

        return $this->render(
            'OpenOrchestraMediaBundle:RenderMedia:video.html.twig',
            array(
                'media_url' => $this->getFileUrl($media->getFilesystemName()),
                'media_type' => $media->getMimeType(),
                'id' => '',
                'class' => '',
                'style' => $style,
                'width' => 320,
                'height' => 240
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
            'OpenOrchestraMediaBundle:RenderMedia:video.html.twig',
            array(
                'media_url' => $this->getFileUrl($media->getFilesystemName()),
                'media_type' => $media->getMimeType(),
                'id' => $options['id'],
                'class' => $options['class'],
                'style' => $options['style'],
                'width' => $options['width'],
                'height' => $options['height']
            )
        );
    }

    /**
     * @param array  $options
     * @param string $method     the method requiring the validation
     *
     * @throws BadOptionException
     * @throws MissingOptionException
     */
    protected function validateOptions(array $options, $method)
    {
        $options = parent::validateOptions($options, $method);

        $options = $this->setOptionIfNotSet($options, 'width', 0);
        $options = $this->setOptionIfNotSet($options, 'height', 0);

        $this->checkIfInteger($options, 'width', __METHOD__);
        $this->checkIfInteger($options, 'height', __METHOD__);

        return $options;
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
        return 'video';
    }
}
