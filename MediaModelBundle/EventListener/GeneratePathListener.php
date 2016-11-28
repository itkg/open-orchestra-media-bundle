<?php

namespace OpenOrchestra\MediaModelBundle\EventListener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\PostFlushEventArgs;
use OpenOrchestra\Media\Model\FolderInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class GeneratePathListener
 */
class GeneratePathListener implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public $folders;

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $this->setPath($event);
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $this->setPath($event);
    }

    /**
     * @param PostFlushEventArgs $event
     */
    public function postFlush(PostFlushEventArgs $event)
    {
        if (!empty($this->folders)) {
            $documentManager = $event->getDocumentManager();
            foreach ($this->folders as $folder) {
                $documentManager->persist($folder);
            }
            $this->folders = array();
            $documentManager->flush();
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function setPath(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        if ($document instanceof FolderInterface) {
            $folderRepository = $this->container->get('open_orchestra_media.repository.media_folder');
            $folderId = $document->getFolderId();
            $path = '';
            $parentFolder = $document->getParent();
            if ($parentFolder instanceof FolderInterface) {
                $path = $parentFolder->getPath();
            }
            $path .= '/' . $folderId;
            if ($path != $document->getPath()) {
                $document->setPath($path);
                $this->folders[] = $document;
                $childFolders = $folderRepository->findSubTreeByPath($document->getPath());
                foreach ($childFolders as $childFolder) {
                    $this->folders[] = $childFolder;
                    $childFolder->setPath(preg_replace('/'.preg_quote($document->getPath(), '/').'(.*)/', $path.'$1', $childFolder->getPath()));
                }
            }
        }
    }
}
