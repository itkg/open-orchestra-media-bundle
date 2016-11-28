<?php

namespace OpenOrchestra\MediaModelBundle\Repository;

use Doctrine\Common\Collections\Collection;
use OpenOrchestra\Media\Repository\FolderRepositoryInterface;
use OpenOrchestra\ModelInterface\Repository\FieldAutoGenerableRepositoryInterface;
use OpenOrchestra\Repository\AbstractAggregateRepository;

/**
 * Class FolderRepository
 */
class FolderRepository extends AbstractAggregateRepository implements FolderRepositoryInterface, FieldAutoGenerableRepositoryInterface
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

    public function findSubTreeByPath($path)
    {
        $qa = $this->createAggregationQuery();
        $qa->match(array('path' => new \MongoRegex('/'.preg_quote($path).'.+/')));

        return $this->hydrateAggregateQuery($qa);
    }

    /**
     * @param string $nodeId
     *
     * @return boolean
     */
    public function testUniquenessInContext($folderId)
    {
        $qa = $this->createAggregationQuery();
        $qa->match(array('folderId' => $folderId));

        return $this->countDocumentAggregateQuery($qa) > 0;
    }
}
