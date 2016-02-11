<?php

namespace OpenOrchestra\MediaModelBundle\EventListener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use OpenOrchestra\BackofficeBundle\Model\GroupInterface;
use OpenOrchestra\ModelInterface\Model\SiteInterface;

/**
 * Class AddMediaFolderGroupRoleForGroupListener
 */
class AddMediaFolderGroupRoleForGroupListener extends AbstractMediaFolderGroupRoleListener
{
    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $document = $event->getDocument();
        if ($document instanceof GroupInterface && ($site = $document->getSite()) instanceof SiteInterface) {
            $siteId = $site->getSiteId();
            $folders = $this->container->get('open_orchestra_media.repository.media_folder')->findBySiteId($siteId);
            $mediaFolderRoles = $this->getMediaFolderRoles();
            foreach ($folders as $folder) {
                $accessType = $this->getFolderAccessType($folder);
                foreach ($mediaFolderRoles as $role => $translation) {
                    $mediaFolderGroupRole = $this->createMediaFolderGroupRole($folder, $document, $role, $accessType);
                    $document->addMediaFolderRole($mediaFolderGroupRole);
                }
            }
        }
    }
}
