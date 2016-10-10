<?php

namespace OpenOrchestra\MediaModelBundle\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\DocumentRepository;
use OpenOrchestra\Media\Repository\FolderRepositoryInterface;

/**
 * Class FolderRepository
 */
class FolderRepository extends DocumentRepository implements FolderRepositoryInterface
{
    /**
     * @param string $siteId
     *
     * @return Collection
     */
    public function findAllRootFolderBySiteId($siteId)
    {
        $qb = $this->createQueryBuilder();
        $qb->field('parent')->equals(null);
        $qb->field('siteId')->equals($siteId);

        return $qb->getQuery()->execute();
    }

    /**
     * @param string $parentId
     * @param string $siteId
     *
     * @throws \Exception
     *
     * @return Collection
     */
    public function findByParentAndSite($parentId, $siteId)
    {
        return $this->findBySiteId($siteId, $parentId);
    }

    /**
     * @param string      $siteId
     * @param string|null $parentId
     *
     * @throws \Exception
     *
     * @return Collection
     */
    public function findBySiteId($siteId, $parentId = null)
    {
        $qb = $this->createQueryBuilder();
        if ($parentId) {
            $qb->field('parent.$id')->equals(new \MongoId($parentId));
        }
        $qb->field('siteId')->equals($siteId);

        return $qb->getQuery()->execute();
    }
}
