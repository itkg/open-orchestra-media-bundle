<?php

namespace OpenOrchestra\MediaBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use OpenOrchestra\Media\MediaEvents;
use OpenOrchestra\Media\Event\MediaEvent;
use OpenOrchestra\DisplayBundle\Manager\CacheableManager;
use OpenOrchestra\BaseBundle\Manager\TagManager;

/**
 * Class MediaSubscriber
 */
class MediaSubscriber implements EventSubscriberInterface
{
    protected $cacheableManager;
    protected $tagManager;

    /**
     * @param CacheableManager $cacheableManager
     * @param TagManager       $tagManager
     */
    public function __construct(CacheableManager $cacheableManager, TagManager $tagManager)
    {
        $this->cacheableManager = $cacheableManager;
        $this->tagManager = $tagManager;
    }

    /**
     * Triggered when a media changes
     * 
     * @param MediaEvent $event
     */
    public function cropMedia(MediaEvent $event)
    {
        $media = $event->getMedia();

        $this->cacheableManager->invalidateTags(
            array(
                $this->tagManager->formatMediaIdTag($media->getId())
            )
        );
    }
        public function deleteMedia(MediaEvent $event)
    {
        $media = $event->getMedia();

        $this->cacheableManager->invalidateTags(
            array(
                $this->tagManager->formatMediaIdTag($media->getId())
            )
        );
    }
    
    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            MediaEvents::MEDIA_CROP => 'cropMedia',
            MediaEvents::MEDIA_DELETE => 'deleteMedia'
       );
    }
}