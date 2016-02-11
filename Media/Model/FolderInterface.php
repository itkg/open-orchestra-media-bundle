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
     * @return Collection
     */
    public function getSubFolders();

    /**
     * @param string $siteId
     *
     * @return Collection
     */
    public function getSubFoldersBySiteId($siteId);

    /**
     * @param FolderInterface $subFolder
     */
    public function addSubFolder(FolderInterface $subFolder);

    /**
     * @param FolderInterface $subFolder
     */
    public function removeSubFolder(FolderInterface $subFolder);

    /**
     * @return Collection
     */
    public function getSites();

    /**
     * @param string $siteId
     *
     * @return bool
     */
    public function hasSite($siteId);

    /**
     * @param array $site
     */
    public function addSite(array $site);

    /**
     * @param array $site
     */
    public function removeSite(array $site);
}
