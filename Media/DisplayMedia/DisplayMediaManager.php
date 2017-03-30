<?php

namespace OpenOrchestra\Media\DisplayMedia;

use OpenOrchestra\Media\Model\MediaInterface;

/**
 * Class DisplayMediaManager
 */
class DisplayMediaManager
{
    protected $strategies = array();
    protected $defaultStrategy;

    /**
     * @param DisplayMediaInterface $defaultStrategy
     */
    public function __construct(DisplayMediaInterface $defaultStrategy)
    {
        $this->defaultStrategy = $defaultStrategy;
    }

    /**
     * @param DisplayMediaInterface $strategy
     */
    public function addStrategy(DisplayMediaInterface $strategy)
    {
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
     * @param string         $alt
     * @param string         $legend
     *
     * @return string
     */
    public function displayMediaForWysiwyg(MediaInterface $media, $format = '', $alt = '', $legend = '')
    {
        /** @var DisplayMediaInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($media)) {
                return $strategy->displayMediaForWysiwyg($media, $format, $alt, $legend);
            }
        }

        return $this->defaultStrategy->displayMediaForWysiwyg($media, $format, $alt, $legend);
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
