<?php

namespace OpenOrchestra\Media\Repository;

use Doctrine\Common\Collections\Collection;
use OpenOrchestra\Media\Model\MediaInterface;
use OpenOrchestra\ModelInterface\Repository\RepositoryTrait\UseTrackableTraitInterface;
use OpenOrchestra\Pagination\Configuration\PaginateFinderConfiguration;

/**
 * Interface MediaRepositoryInterface
 */
interface MediaRepositoryInterface extends UseTrackableTraitInterface
{
    /**
     * @param string $folderId
     *
     * @return Collection
     */
    public function findByFolderId($folderId);

    /**
     * @param string $folderId
     *
     * @return Collection
     */
    public function countByFolderId($folderId);

    /**
     * @param string $keywords
     *
     * @return array
     */
    public function findByKeywords($keywords);

    /**
     * @param string $id
     *
     * @return MediaInterface
     */
    public function find($id);

    /**
     * @param string $name
     *
     * @return MediaInterface
     */
    public function findOneByName($name);

    /**
     * @param array $mediaIds
     *
     * @throws \Exception
     */
    public function removeMedias(array $mediaIds);

    /**
     * @param $siteId
     */
    public function removeAllBySiteId($siteId);

    /**
     * @param PaginateFinderConfiguration $configuration
     * @param string                      $siteId
     *
     * @return array
     */
    public function findForPaginate(PaginateFinderConfiguration $configuration, $siteId);

    /**
     * @param string      $siteId
     * @param string|null $type
     * @param array|null  $foldersId
     * @return int
     */
    public function count($siteId, $type = null, $foldersId = null);

    /**
     * @param PaginateFinderConfiguration $configuration
     * @param string                      $siteId
     *
     * @return int
     */
    public function countWithFilter(PaginateFinderConfiguration $configuration, $siteId);

    /**
     * @param $siteId
     *
     * @return Collection
     */
    public function findWithUseReferences($siteId);

    /**
     * @param string $mediaId
     * @param string $type
     *
     * @return boolean
     */
    public function isMediaTypeOf($mediaId, $type);
}
