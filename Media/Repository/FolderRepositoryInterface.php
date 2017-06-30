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
     * @deprecated
     * @return Collection
     */
    public function findByParentAndSite($parentId, $siteId);

    /**
     * @param string $path
     * @param string $siteId
     *
     * @throws \Exception
     *
     * @return Collection
     */
    public function findByPathAndSite($path, $siteId);

    /**
     * @param string $siteId
     *
     * @return array
     */
    public function findFolderTree($siteId);

    /**
     * @param string $id
     *
     * @return FolderInterface
     */
    public function findOneById($id);

    /**
     * @param $siteId
     */
    public function removeAllBySiteId($siteId);

    /**
     * @param string $parentId
     * @param string $siteId
     *
     * @return number
     */
    public function countChildren($parentId, $siteId);
}
