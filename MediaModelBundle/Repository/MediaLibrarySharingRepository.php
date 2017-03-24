<?php

namespace OpenOrchestra\MediaModelBundle\Repository;

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
}
