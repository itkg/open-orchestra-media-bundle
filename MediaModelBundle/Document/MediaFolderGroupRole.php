<?php

namespace OpenOrchestra\MediaModelBundle\Document;

use OpenOrchestra\GroupBundle\Document\AbstractGroupRole;
use OpenOrchestra\Media\Model\MediaFolderGroupRoleInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class MediaFolderGroupRole
 *
 * @ODM\EmbeddedDocument
 */
class MediaFolderGroupRole extends AbstractGroupRole implements MediaFolderGroupRoleInterface
{
    /**
     * @var string
     *
     * @ODM\Field(type="string")
     */
    protected $folderId;

    /**
     * @return string
     */
    public function getFolderId()
    {
        return $this->folderId;
    }

    /**
     * @param string $folderId
     */
    public function setFolderId($folderId)
    {
        $this->folderId = $folderId;
    }
}
