<?php

namespace OpenOrchestra\Media\Model;

use Doctrine\Common\Collections\Collection;
use OpenOrchestra\ModelInterface\Model\BlameableInterface;
use OpenOrchestra\ModelInterface\Model\TimestampableInterface;

/**
 * Interface FolderInterface
 */
interface FolderInterface extends TimestampableInterface, BlameableInterface
{
    const GROUP_ROLE_TYPE = 'folder';
    const ROOT_PARENT_ID = '-';

    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return FolderInterface
     */
    public function getParent();

    /**
     * @param FolderInterface $parent
     */
    public function setParent(FolderInterface $parent);

    /**
     * Set path
     *
     * @param string $path
     */
    public function setPath($path);

    /**
     * Get path
     *
     * @return string $path
     */
    public function getPath();

    /**
     * @return Collection
     */
    public function getSubFolders();

    /**
     * @param FolderInterface $subFolder
     */
    public function addSubFolder(FolderInterface $subFolder);

    /**
     * @param FolderInterface $subFolder
     */
    public function removeSubFolder(FolderInterface $subFolder);

    /**
     * @return string
     */
    public function getSiteId();

    /**
     * @param string $siteId
     */
    public function setSiteId($siteId);
}
