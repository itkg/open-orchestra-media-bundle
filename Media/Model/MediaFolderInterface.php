<?php

namespace OpenOrchestra\Media\Model;

use Doctrine\Common\Collections\Collection;

/**
 * Interface MediaFolderInterface
 */
interface MediaFolderInterface extends FolderInterface
{
    const ENTITY_TYPE = 'media_folder';

    /**
     * Set folderId
     *
     * @param string $folderId
     */
    public function setFolderId($folderId);

    /**
     * Get fodlereId
     *
     * @return string
     */
    public function getFolderId();
}
