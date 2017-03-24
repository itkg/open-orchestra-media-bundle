<?php

namespace OpenOrchestra\MediaModelBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use OpenOrchestra\Media\Model\MediaLibrarySharingInterface;

/**
 * Class MediaLibrarySharing
 *
 * @ODM\Document(
 *   collection="media_library_sharing",
 *   repositoryClass="OpenOrchestra\MediaModelBundle\Repository\MediaLibrarySharingRepository"
 * )
 */
class MediaLibrarySharing implements MediaLibrarySharingInterface
{
    /**
     * @var string $id
     *
     * @ODM\Id
     */
    protected $id;

    /**
     * @var string
     *
     * @ODM\Field(type="string")
     */
    protected $siteId;

    /**
     * @var array
     *
     * @ODM\Field(type="collection")
     */
    protected $allowedSites = array();

    /**
     * @return string
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @param string $siteId
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

    /**
     * @return array
     */
    public function getAllowedSites()
    {
        return $this->allowedSites;
    }

    /**
     * @param array $allowedSites
     */
    public function setAllowedSites(array $allowedSites)
    {
        $this->allowedSites = $allowedSites;
    }
}
