<?php

namespace OpenOrchestra\MediaModelBundle\Repository;

use Doctrine\Common\Collections\Collection;
use OpenOrchestra\Media\Model\MediaLibrarySharingInterface;
use OpenOrchestra\Media\Repository\MediaLibrarySharingRepositoryInterface;
use OpenOrchestra\Repository\AbstractAggregateRepository;

/**
 * Class MediaLibrarySharingRepository
 */
class MediaLibrarySharingRepository extends AbstractAggregateRepository implements MediaLibrarySharingRepositoryInterface
{
    /**
     * @param string $siteId
     *
     * @return null|MediaLibrarySharingInterface
     */
    public function findOneBySiteId($siteId)
    {
        return parent::findOneBy(array(
            'siteId' => $siteId
        ));
    }

    /**
     * @param string $siteId
     *
     * @return Collection
     */
    public function findAllowedSites($siteId)
    {
        $qb = $this->createQueryBuilder();
        $qb->field('allowedSites')->in(array($siteId));

        return $qb->getQuery()->execute();
    }
}
