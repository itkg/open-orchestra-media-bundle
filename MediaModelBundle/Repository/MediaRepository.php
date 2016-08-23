<?php

namespace OpenOrchestra\MediaModelBundle\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\DocumentRepository;
use OpenOrchestra\Media\Model\MediaInterface;
use OpenOrchestra\Media\Repository\MediaRepositoryInterface;
use OpenOrchestra\ModelBundle\Repository\RepositoryTrait\KeywordableTrait;
use OpenOrchestra\ModelInterface\Repository\RepositoryTrait\KeywordableTraitInterface;
use OpenOrchestra\ModelInterface\Model\UseTrackableInterface;

/**
 * Class MediaRepository
 */
class MediaRepository extends DocumentRepository implements MediaRepositoryInterface, KeywordableTraitInterface
{
    use KeywordableTrait;

    /**
     * @param string $folderId
     *
     * @return Collection
     */
    public function findByFolderId($folderId)
    {
        $qb = $this->createQueryBuilder();

        $qb->field('mediaFolder.id')->equals($folderId);

        return $qb->getQuery()->execute();
    }

    /**
     * @param string $folderId
     * @param string $mediaType
     *
     * @return Collection
     */
    public function findByFolderIdAndMediaType($folderId, $mediaType)
    {
        $qb = $this->createQueryBuilder();

        $qb->field('mediaFolder.id')->equals($folderId);
        $qb->field('mediaType')->equals($mediaType);

        return $qb->getQuery()->execute();
    }

    /**
     * @param string $keywords
     *
     * @return array
     */
    public function findByKeywords($keywords)
    {
        $qb = $this->createQueryBuilder();
        $qb->setQueryArray($this->transformConditionToMongoCondition($keywords));

        return $qb->getQuery()->execute();
    }

    /**
     * @param string $name
     *
     * @return MediaInterface
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(array('name' => $name));
    }

    /**
     * @param string $nodeId
     *
     * @return array
     */
    public function findUsedInNode($nodeId)
    {
        $qb = $this->createQueryBuilder();

        $qb->field('useReferences.' . UseTrackableInterface::KEY_NODE . '.' . $nodeId)->exists('true');

        return $qb->getQuery()->execute();
    }

    /**
     * @param string $contentId
     *
     * @return array
     */
    public function findUsedInContent($contentId)
    {
        $qb = $this->createQueryBuilder();

        $qb->field('useReferences.' . UseTrackableInterface::KEY_CONTENT . '.' . $contentId)->exists('true');

        return $qb->getQuery()->execute();
    }
}
