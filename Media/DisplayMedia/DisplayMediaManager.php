<?php

namespace OpenOrchestra\Media\DisplayMedia;

use OpenOrchestra\Media\Model\MediaInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class DisplayMediaManager
 */
class DisplayMediaManager
{
    protected $strategies = array();
    protected $router;
    protected $defaultStrategy;

    /**
     * @param RouterInterface       $router
     * @param DisplayMediaInterface $defaultStrategy
     */
    public function __construct(RouterInterface $router, DisplayMediaInterface $defaultStrategy)
    {
        $this->router = $router;
        $this->defaultStrategy = $defaultStrategy;
    }

    /**
     * @param DisplayMediaInterface $strategy
     */
    public function addStrategy(DisplayMediaInterface $strategy)
    {
        $strategy->setRouter($this->router);
        $this->strategies[$strategy->getName()] = $strategy;
    }

    /**
     * @param MediaInterface $media
     *
     * @return String
     */
    public function displayPreview(MediaInterface $media)
    {
        /** @var DisplayMediaInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($media)) {
                return $strategy->displayPreview($media);
            }
        }

        return $this->defaultStrategy->displayPreview($media);
    }

    /**
     * @param MediaInterface $media
     * @param array          $options
     *
     * @return string
     */
    public function renderMedia(MediaInterface $media, array $options = array())
    {
        /** @var DisplayMediaInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($media)) {
                return $strategy->renderMedia($media, $options);
            }
        }

        return $this->defaultStrategy->renderMedia($media, $options);
    }

    /**
     * @param MediaInterface $media
     * @param string         $format
     * @param string         $style
     *
     * @return string
     */
    public function displayMediaForWysiwyg(MediaInterface $media, $format = '', $style = '')
    {
        /** @var DisplayMediaInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($media)) {
                return $strategy->displayMediaForWysiwyg($media, $format, $style);
            }
        }

        return $this->defaultStrategy->displayMediaForWysiwyg($media, $format, $style);
    }

    /**
     * @param MediaInterface $media
     * @param String         $format
     *
     * @return String
     */
    public function getMediaFormatUrl(MediaInterface $media, $format)
    {
        /** @var DisplayMediaInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($media)) {
                return $strategy->getMediaFormatUrl($media, $format);
            }
        }

        return $this->defaultStrategy->getMediaFormatUrl($media, $format);
    }
}
