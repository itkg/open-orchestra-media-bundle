<?php

namespace OpenOrchestra\Media\DisplayMedia\Strategies;

use OpenOrchestra\Media\DisplayMedia\DisplayMediaInterface;
use OpenOrchestra\Media\Model\MediaInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use OpenOrchestra\Media\Exception\MissingOptionException;
use OpenOrchestra\Media\Exception\BadOptionFormatException;
use OpenOrchestra\Media\Exception\BadOptionException;

/**
 * Class AbstractDisplayMediaStrategy
 */
abstract class AbstractDisplayMediaStrategy implements DisplayMediaInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected $validOptions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->validOptions = array('format', 'style', 'class', 'id', 'alt', 'legend');
    }

    /**
     * @param MediaInterface $media
     *
     * @return String
     */
    public function displayPreview(MediaInterface $media)
    {
        return $this->getFileUrl($media->getThumbnail());
    }

    /**
     * @param array  $options
     * @param string $method     the method requiring the validation
     *
     * @return array
     *
     * @throws BadOptionException
     * @throws MissingOptionException
     */
    protected function validateOptions(array $options, $method)
    {
        foreach ($options as $key => $value) {
            if (!in_array($key, $this->validOptions)) {
                throw new BadOptionException($key, $this->validOptions, $method);
            }
        }

        $options = $this->setOptionIfNotSet($options, 'format', MediaInterface::MEDIA_ORIGINAL);
        $options = $this->setOptionIfNotSet($options, 'style', '');
        $options = $this->setOptionIfNotSet($options, 'class', '');
        $options = $this->setOptionIfNotSet($options, 'id', '');
        $options = $this->setOptionIfNotSet($options, 'alt', '');
        $options = $this->setOptionIfNotSet($options, 'legend', '');

        $this->checkIfString($options, 'format', $method);
        $this->checkIfString($options, 'style', $method);
        $this->checkIfString($options, 'class', $method);
        $this->checkIfString($options, 'id', $method);
        $this->checkIfString($options, 'alt', $method);
        $this->checkIfString($options, 'legend', $method);

        return $options;
    }

    /**
     * @param array  $options
     * @param string $optionName
     * @param mixed  $optionDefaultValue
     *
     * @return array
     */
    protected function setOptionIfNotSet(array $options, $optionName, $optionDefaultValue)
    {
        if (!isset($options[$optionName])) {
            $options[$optionName] = $optionDefaultValue;
        }

        return $options;
    }

    /**
     * @param array  $options
     * @param string $optionName
     * @param string $method
     *
     * @throws BadOptionFormatException
     */
    protected function checkIfString(array $options, $optionName, $method)
    {
        if (!is_string($options[$optionName])) {
            throw new BadOptionFormatException($optionName, 'string', $method);
        }
    }

    /**
     * @param array  $options
     * @param string $optionName
     * @param string $method
     *
     * @throws BadOptionFormatException
     */
    protected function checkIfInteger(array $options, $optionName, $method)
    {
        if (!is_int($options[$optionName])) {
            throw new BadOptionFormatException($optionName, 'integer', $method);
        }
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
            'OpenOrchestraMediaBundle:BBcode/WysiwygDisplay:thumbnail.html.twig',
            array(
                'media_url'    => $this->getFileUrl($media->getThumbnail()),
                'media_alt'    => $alt,
                'media_id'     => $media->getId(),
                'media_legend' => $legend
            )
        );
    }

    /**
     * Render the $template with $params
     *
     * @param string $template
     * @param array  $params
     *
     * @return string
     */
    protected function render($template, $params)
    {
        return $this->container->get('templating')->render($template, $params);
    }

    /**
     * Return url to a file stored with the UploadedFileManager
     *
     * @param string $storageKey
     *
     * @return string
     */
    protected function getFileUrl($storageKey)
    {
        return $this->container->get('open_orchestra_media.manager.storage')->getUrl($storageKey);
    }
}
