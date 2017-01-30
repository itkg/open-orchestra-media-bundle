<?php

namespace OpenOrchestra\MediaModelBundle\Repository;

use Doctrine\Common\Collections\Collection;
use OpenOrchestra\Media\Model\MediaInterface;
use OpenOrchestra\Media\Repository\MediaRepositoryInterface;
use OpenOrchestra\ModelBundle\Repository\RepositoryTrait\KeywordableTrait;
use OpenOrchestra\ModelInterface\Repository\RepositoryTrait\KeywordableTraitInterface;
use OpenOrchestra\ModelBundle\Repository\RepositoryTrait\UseTrackableTrait;
use OpenOrchestra\Pagination\Configuration\PaginateFinderConfiguration;
use OpenOrchestra\Repository\AbstractAggregateRepository;
use Solution\MongoAggregation\Pipeline\Stage;
use OpenOrchestra\Repository\ReferenceAggregateFilterTrait;

/**
 * Class MediaRepository
 */
class MediaRepository extends AbstractAggregateRepository implements MediaRepositoryInterface, KeywordableTraitInterface
{
    use KeywordableTrait;
    use UseTrackableTrait;
    use ReferenceAggregateFilterTrait;

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
     * @param PaginateFinderConfiguration $configuration
     *
     * @return array
     */
    public function findForPaginate(PaginateFinderConfiguration $configuration)
    {
        $qa = $this->createAggregationQuery();

        $this->filterSearch($configuration, $qa);

        $order = $configuration->getOrder();
        if (!empty($order)) {
            $qa->sort($order);
        }

        $qa->skip($configuration->getSkip());
        $qa->limit($configuration->getLimit());

        return $this->hydrateAggregateQuery($qa);
    }

    /**
     * @return int
     */
    public function count()
    {
        $qa = $this->createAggregationQuery();

        return $this->countDocumentAggregateQuery($qa);
    }

    /**
     * @param PaginateFinderConfiguration $configuration
     *
     * @return int
     */
    public function countWithFilter(PaginateFinderConfiguration $configuration)
    {
        $qa = $this->createAggregationQuery();
        $this->filterSearch($configuration, $qa);

        return $this->countDocumentAggregateQuery($qa);
    }

    /**
     * @param PaginateFinderConfiguration $configuration
     * @param Stage                       $qa
     *
     * @return array
     */
    protected function filterSearch(PaginateFinderConfiguration $configuration, Stage $qa)
    {
//         var_dump($configuration);exit();

        $label = $configuration->getSearchIndex('label');
        $language = $configuration->getSearchIndex('language');
        if (null !== $label && '' !== $label && null !== $language && '' !== $language) {
            $filters = $this->getReferenceFilter('keywords', $this->generateFilterKeywordLabel($label));
            $filters['$or'][] = array('titles.' . $language => new \MongoRegex('/.*' . $label . '.*/i'));
            $qa->match($filters);
        }

        $type = $configuration->getSearchIndex('type');
        if (null !== $type && '' !== $type) {
            $qa->match(array('mediaType' => $type));
        }

        return $qa;
    }

    /**
     * @param string $label
     *
     * @return array
     */
    protected function generateFilterKeywordLabel($label)
    {
        return array('label' => new \MongoRegex('/.*' . $label . '.*/i'));
    }
}
