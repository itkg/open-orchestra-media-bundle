<?php

namespace OpenOrchestra\Media\Repository;

use Doctrine\Common\Collections\Collection;
use OpenOrchestra\Media\Model\FolderInterface;

/**
 * Interface FolderRepositoryInterface
 */
interface FolderRepositoryInterface
{
    /**
     * @return Collection
     */
    public function findAllRootFolder();

    /**
     * @param string $siteId
     *
     * @return array
     */
    public function findAllRootFolderBySiteId($siteId);

    /**
     * @param string $id
     *
     * @return FolderInterface
     */
    public function find($id);

    /**
     * @param string $parentId
     * @param string $siteId
     *
     * @throws \Exception
     *
     * @return Collection
     */
    public function findByParentAndSite($parentId, $siteId);

    /**
     * @param string      $siteId
     * @param string|null $parentId
     *
     * @throws \Exception
     *
     * @return Collection
     */
    public function findBySiteId($siteId, $parentId = null);
}
