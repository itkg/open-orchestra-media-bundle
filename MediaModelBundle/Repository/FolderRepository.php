<?php

namespace OpenOrchestra\MediaModelBundle\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\MongoDB\Query\Builder;
use OpenOrchestra\Media\Model\FolderInterface;
use OpenOrchestra\Media\Repository\FolderRepositoryInterface;
use OpenOrchestra\ModelInterface\Repository\FieldAutoGenerableRepositoryInterface;
use OpenOrchestra\Repository\AbstractAggregateRepository;

/**
 * Class FolderRepository
 */
class FolderRepository extends AbstractAggregateRepository implements FolderRepositoryInterface, FieldAutoGenerableRepositoryInterface
{

    /**
     * @param string $parentId
     * @param string $siteId
     *
     * @throws \Exception
     *
     * @deprecated
     * @return Collection
     */
    public function findByParentAndSite($parentId, $siteId)
    {
        $qb = $this->createQueryBuilder();
        if ($parentId) {
            $qb->field('parent.$id')->equals(new \MongoId($parentId));
        }
        $qb->field('siteId')->equals($siteId);

        return $qb->getQuery()->execute();
     }

    /**
     * @param string $path
     * @param string $siteId
     *
     * @throws \Exception
     *
     * @return Collection
     */
    public function findByPathAndSite($path, $siteId)
    {
        $qb = $this->createQueryBuilder();
        $qb->field('siteId')->equals($siteId)
            ->field('path')->equals(new \MongoRegex('/^'.$path.'(\/.*)?$/'));

        return $qb->getQuery()->execute();
    }

    /**
     * @param string $parentId
     *
     * @throws \Exception
     *
     * @return Builder
     */
    public function findBySite($siteId)
    {
        $qb = $this->createQueryBuilder();
        $qb->field('siteId')->equals($siteId);

        return $qb;
    }

    /**
     * @param string $path
     *
     * @return array
     */
    public function findSubTreeByPath($path)
    {
        $qa = $this->createAggregationQuery();
        $qa->match(array('path' => new \MongoRegex('/'.preg_quote($path).'(\/*)+/')));

        return $this->hydrateAggregateQuery($qa);
    }

    /**
     * @param string $folderId
     *
     * @return boolean
     */
    public function testUniquenessInContext($folderId)
    {
        $qa = $this->createAggregationQuery();
        $qa->match(array('folderId' => $folderId));

        return $this->countDocumentAggregateQuery($qa) > 0;
    }

    /**
     * @param string $siteId
     *
     * @return array
     */
    public function findFolderTree($siteId)
    {
        $qa = $this->createAggregationQuery();
        $qa->match(array('siteId' => $siteId));
        $folders = $qa->getQuery()->aggregate()->toArray();

        if (empty($folders)) {
            return array();
        }

        $rootFolders = array_filter($folders, function($folder, $key) {
            if (isset($folder['parent'])) {
                return false;
            }
            return true;
        }, ARRAY_FILTER_USE_BOTH);

        $foldersToPlace = array_udiff($folders, $rootFolders, function($folder1, $folder2) {
            if ($folder1 === $folder2) {
                return 0;
            }
            return -1;
        });

        $tree = array();
        foreach ($rootFolders as $rootFolder) {
            $tree[] = array(
                'folder'   => $rootFolder,
                'children' => $this->getChildren($rootFolder, $foldersToPlace)
            );
        }

        return $tree;
    }

    /**
     * @param string $parent
     * @param array  $folders
     *
     * @return array
     */
    protected function getChildren($parent, array $folders)
    {
        $children = array();
        foreach ($folders as $key => $folder) {
            if (isset($folder['parent']) && $parent['_id'] == $folder['parent']['$id']) {
                unset($folders[$key]);
                $children[] = array(
                    'folder'   => $folder,
                    'children' => $this->getChildren($folder, $folders));
            }
        }

        return $children;
    }

    /**
     * @param string $parentId
     * @param string $siteId
     *
     * @return number
     */
    public function countChildren($parentId, $siteId)
    {
        $qa = $this->createAggregationQuery();
        $qa->match(
            array(
                'siteId'     => $siteId,
                'parent.$id' => new \MongoId($parentId)
            )
        );

        return $this->countDocumentAggregateQuery($qa);
    }

    /**
     * @param string $id
     *
     * @return FolderInterface
     */
    public function findOneById($id) {
        return $this->find($id);
    }

    /**
     * @param $siteId
     */
    public function removeAllBySiteId($siteId)
    {
        $qb = $this->createQueryBuilder();
        $qb->remove()
            ->field('siteId')->in($siteId)
            ->getQuery()
            ->execute();
    }
}
