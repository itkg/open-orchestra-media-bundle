<?php

namespace OpenOrchestra\MediaModelBundle\EventListener;

use OpenOrchestra\BackofficeBundle\Model\GroupInterface;
use OpenOrchestra\Media\Model\FolderInterface;
use OpenOrchestra\Media\Model\MediaFolderGroupRoleInterface;
use OpenOrchestra\MediaAdminBundle\Exceptions\MediaFolderGroupRoleNotFoundException;
use OpenOrchestra\MediaModelBundle\Document\MediaFolderGroupRole;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class AbstractMediaFolderGroupRoleListener
 */
class AbstractMediaFolderGroupRoleListener
{
    use ContainerAwareTrait;

    protected $mediaFolderGroupRoleClass;

    /**
     * @param string $mediaFolderGroupRoleClass
     */
    public function __construct($mediaFolderGroupRoleClass)
    {
        $this->mediaFolderGroupRoleClass = $mediaFolderGroupRoleClass;
    }

    /**
     * @return array
     */
    protected function getMediaFolderRoles()
    {
        $collector = $this->container->get('open_orchestra_backoffice.collector.backoffice_role');

        return $collector->getRolesByType('media|media_folder');
    }

    /**
     * @param FolderInterface $folder
     *
     * @return string
     */
    protected function getFolderAccessType(FolderInterface $folder)
    {
        $accessType = MediaFolderGroupRoleInterface::ACCESS_INHERIT;
        if (null === $folder->getParent()) {
            $accessType = MediaFolderGroupRoleInterface::ACCESS_GRANTED;
        }

        return $accessType;
    }

    /**
     * @param FolderInterface $folder
     * @param GroupInterface  $group
     * @param string          $role
     * @param string          $accessType
     *
     * @return MediaFolderGroupRoleInterface
     * @throws MediaFolderGroupRoleNotFoundException
     */
    protected function createMediaFolderGroupRole($folder, $group, $role, $accessType)
    {
        /** @var $mediaFolderGroupRole MediaFolderGroupRoleInterface */
        $mediaFolderGroupRole = new $this->mediaFolderGroupRoleClass();
        $mediaFolderGroupRole->setFolderId($folder->getId());
        $mediaFolderGroupRole->setRole($role);
        $mediaFolderGroupRole->setAccessType($accessType);
        $isGranted = (MediaFolderGroupRoleInterface::ACCESS_DENIED === $accessType) ? false : true;
        if (MediaFolderGroupRoleInterface::ACCESS_INHERIT === $accessType) {
            $parentFolderRole = $group->getMediaFolderRoleByMediaFolderAndRole($folder->getParent()->getId(), $role);
            if ($parentFolderRole instanceof MediaFolderGroupRole) {
                $isGranted = $parentFolderRole->isGranted();
            }
        }
        $mediaFolderGroupRole->setGranted($isGranted);

        return $mediaFolderGroupRole;
    }
}
