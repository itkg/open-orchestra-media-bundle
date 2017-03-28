<?php

namespace OpenOrchestra\Media\Repository;

use Doctrine\Common\Collections\Collection;
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

    /**
     * @param string $siteId
     *
     * @return Collection
     */
    public function findAllowedSites($siteId);
}
