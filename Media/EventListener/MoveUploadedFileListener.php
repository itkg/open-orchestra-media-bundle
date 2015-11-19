<?php

namespace OpenOrchestra\Media\EventListener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use OpenOrchestra\Media\Manager\SaveMediaManagerInterface;
use OpenOrchestra\Media\Model\MediaInterface;

/**
 * Class MoveUploadedFileListener
 */
class MoveUploadedFileListener
{

    protected $saveFileManager;

    /**
     * @param SaveMediaManagerInterface $saveFileManager
     */
    public function __construct(SaveMediaManagerInterface $saveFileManager)
    {
        $this->saveFileManager = $saveFileManager;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $documents = $event->getDocument();
        if (!is_array($documents)) {
            $documents = array($documents);
        }
        foreach($documents as $document) {
            if ($document instanceof MediaInterface) {
                $this->saveFileManager->saveMedia($document);
            }
        }
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postPersist(LifecycleEventArgs $event)
    {
        $documents = $event->getDocument();
        if (!is_array($documents)) {
            $documents = array($documents);
        }
        foreach($documents as $document) {
            if ($document instanceof MediaInterface) {
                $this->saveFileManager->uploadMedia($document);
            }
        }
    }
}
