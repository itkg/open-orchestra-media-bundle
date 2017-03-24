<?php

namespace OpenOrchestra\Media\Repository;

use OpenOrchestra\Media\Model\MediaLibrarySharingInterface;

/**
 * Interface MediaLibrarySharingRepositoryInterface
 */
interface MediaLibrarySharingRepositoryInterface
{
    /**
     * @param string $siteId
     *
     * @return null|MediaLibrarySharingInterface
     */
    public function findOneBySiteId($siteId);
}
