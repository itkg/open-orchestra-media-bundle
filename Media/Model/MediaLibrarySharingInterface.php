<?php

namespace OpenOrchestra\Media\Model;

/**
 * Interface MediaInterface
 */
interface MediaLibrarySharingInterface
{
    /**
     * @return string
     */
    public function getSiteId();

    /**
     * @param string $siteId
     */
    public function setSiteId($siteId);

    /**
     * @return array
     */
    public function getAllowedSites();

    /**
     * @param array $allowedSites
     */
    public function setAllowedSites(array $allowedSites);
}
