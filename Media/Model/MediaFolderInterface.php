<?php

namespace OpenOrchestra\Media\Model;

use Doctrine\Common\Collections\Collection;

/**
 * Interface MediaFolderInterface
 */
interface MediaFolderInterface extends FolderInterface
{
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
}
