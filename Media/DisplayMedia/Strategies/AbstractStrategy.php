<?php

namespace OpenOrchestra\Media\DisplayMedia\Strategies;

use OpenOrchestra\Media\DisplayMedia\DisplayMediaInterface;
use OpenOrchestra\Media\Model\MediaInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use OpenOrchestra\Media\Exception\MissingOptionException;
use OpenOrchestra\Media\Exception\BadOptionFormatException;
use OpenOrchestra\Media\Exception\BadOptionException;

/**
 * Class AbstractStrategy
 */
abstract class AbstractStrategy implements DisplayMediaInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected $router;
    protected $requestStack;
    protected $mediaDomain;
    protected $validOptions;

    /**
     * @param RequestStack $requestStack
     * @param string       $mediaDomain
     */
    public function __construct(RequestStack $requestStack, $mediaDomain = "")
    {
        $this->requestStack = $requestStack;
        $this->mediaDomain = $mediaDomain;
        $this->validOptions = array('format', 'style', 'class', 'id');
    }

    /**
     * Set the router
     *
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
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
     * @deprecated renderMedia is deprecated in AbstractStrategy since version 1.2.0 and will be removed in 2.0.0. Implement it in the strategies
     *
     * @param MediaInterface $media
     * @param array          $options
     *
     * @return string
     */
    public function renderMedia(MediaInterface $media, array $options)
    {
        @trigger_error('The '.__METHOD__.' method is deprecated in AbstractStrategy since version 1.2.0 and will be removed in 2.0.0.'
            . ' Implement it in the strategies instead.', E_USER_DEPRECATED);

        $format = '';
        if (isset($options['format'])) {
            $format = $options['format'];
        }

        $style = '';
        if (isset($options['style'])) {
            $format = $options['style'];
        }

        return $this->displayMedia($media, $format, $style);
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
        foreach ($options as $key => $value) {
            if (!in_array($key, $this->validOptions)) {
                throw new BadOptionException($key, $this->validOptions, $method);
            }
        }

        $options = $this->setOptionIfNotSet($options, 'format', MediaInterface::MEDIA_ORIGINAL);
        $options = $this->setOptionIfNotSet($options, 'style', '');
        $options = $this->setOptionIfNotSet($options, 'class', '');
        $options = $this->setOptionIfNotSet($options, 'id', '');

        $this->checkIfString($options, 'format', $method);
        $this->checkIfString($options, 'style', $method);
        $this->checkIfString($options, 'class', $method);
        $this->checkIfString($options, 'id', $method);

        return $options;
    }

    /**
     * @param array  $options
     * @param string $optionName
     * @param mixed  $optionDefaultValue
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
            'OpenOrchestraMediaBundle:BBcode/WysiwygDisplay:thumbnail.html.twig',
            array(
                'media_url' => $this->getFileUrl($media->getThumbnail()),
                'media_alt' => $media->getAlt($request->getLocale()),
                'media_id' => $media->getId(),
                'style' => $style
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
     * @param string storageKey
     *
     * @return string
     */
    protected function getFileUrl($storageKey)
    {
        return '//' . $this->mediaDomain
            . $this->router->generate('open_orchestra_media_get',
            array('key' => $storageKey),
            UrlGeneratorInterface::ABSOLUTE_PATH
        );
    }
}
