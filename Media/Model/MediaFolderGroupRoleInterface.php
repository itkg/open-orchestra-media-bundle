<?php

namespace OpenOrchestra\Media\Model;

use OpenOrchestra\BackofficeBundle\Model\GroupRoleInterface;

/**
 * Interface MediaFolderGroupRoleInterface
 */
interface MediaFolderGroupRoleInterface extends GroupRoleInterface
{
    /**
     * @return string
     */
    public function getFolderId();

    /**
     * @param string $nodeId
     */
    public function setFolderId($nodeId);

}
