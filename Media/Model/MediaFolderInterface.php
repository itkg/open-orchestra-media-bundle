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
     * @return Collection
     */
    public function getMedias();

    /**
     * @param MediaInterface $media
     */
    public function addMedia(MediaInterface $media);

    /**
     * @param MediaInterface $media
     */
    public function removeMedia(MediaInterface $media);

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
