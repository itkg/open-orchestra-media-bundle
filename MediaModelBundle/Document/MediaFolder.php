<?php

namespace OpenOrchestra\MediaModelBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use OpenOrchestra\Media\Model\MediaFolderInterface;
use OpenOrchestra\Mapping\Annotations as ORCHESTRA;

/**
 * Class MediaFolder
 *
 * @ODM\Document(
 *   repositoryClass="OpenOrchestra\MediaModelBundle\Repository\FolderRepository"
 * )
 * @ORCHESTRA\Document(
 *   generatedField="folderId",
 *   sourceField="names",
 *   serviceName="open_orchestra_media.repository.media_folder",
 * )
 */
class MediaFolder extends Folder implements MediaFolderInterface
{
    /**
     * @var string $folderId
     *
     * @ODM\Field(type="string")
     */
    protected $folderId;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->medias = new ArrayCollection();
    }

    /**
     * Set folderId
     *
     * @param string $folderId
     */
    public function setFolderId($folderId)
    {
        $this->folderId = $folderId;
    }

    /**
     * Get fodlereId
     *
     * @return string
     */
    public function getFolderId()
    {
        return $this->folderId;
    }
}
