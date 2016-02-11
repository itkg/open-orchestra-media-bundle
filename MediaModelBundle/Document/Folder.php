<?php

namespace OpenOrchestra\MediaModelBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Blameable\Traits\BlameableDocument;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use OpenOrchestra\Media\Model\FolderInterface;

/**
 * Class Folder
 *
 * @ODM\Document(
 *   collection="folder",
 *   repositoryClass="OpenOrchestra\MediaModelBundle\Repository\FolderRepository"
 * )
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField("type")
 * @ODM\DiscriminatorMap({"media_folder"="MediaFolder"})
 */
abstract class Folder implements FolderInterface
{
    use BlameableDocument;
    use TimestampableDocument;

    /**
     * @var string $id
     *
     * @ODM\Id
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ODM\Field(type="string")
     */
    protected $name;

    /**
     * @var FolderInterface
     *
     * @ODM\ReferenceOne(targetDocument="OpenOrchestra\Media\Model\FolderInterface", inversedBy="subFolders")
     */
    protected $parent;

    /**
     * @var ArrayCollection
     *
     * @ODM\ReferenceMany(targetDocument="OpenOrchestra\Media\Model\FolderInterface", mappedBy="parent")
     */
    protected $subFolders;

    /**
     * @var Array
     *
     * @ODM\Field(type="collection")
     */
    protected $sites;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subFolders = new ArrayCollection();
        $this->sites = array();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return FolderInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param FolderInterface $parent
     */
    public function setParent(FolderInterface $parent)
    {
        $this->parent = $parent;
        $parent->addSubFolder($this);
    }

    /**
     * @return Collection
     */
    public function getSubFolders()
    {
        return $this->subFolders;
    }

    /**
     * @param string $siteId
     *
     * @return Collection
     */
    public function getSubFoldersBySiteId($siteId)
    {
        return $this->subFolders->filter(function(FolderInterface $folder) use ($siteId) {
            foreach ($folder->getSites() as $folderSite) {
                if ($folderSite['siteId'] === $siteId) {
                    return true;
                }
            }

            return count($folder->getSites()) === 0;
        });
    }

    /**
     * @param FolderInterface $subFolder
     */
    public function addSubFolder(FolderInterface $subFolder)
    {
        $this->subFolders->add($subFolder);
    }

    /**
     * @param FolderInterface $subFolder
     */
    public function removeSubFolder(FolderInterface $subFolder)
    {
        $this->subFolders->removeElement($subFolder);
    }

    /**
     * @return ArrayCollection
     */
    public function getSites()
    {
        return $this->sites;
    }

    /**
     * @param string $siteId
     *
     * @return bool
     */
    public function hasSite($siteId)
    {
        foreach ($this->sites as $site) {
            if (isset($site['siteId']) && $site['siteId'] === $siteId) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $site
     */
    public function addSite(array $site)
    {
        $this->sites[] = $site;
    }

    /**
     * @param array $site
     */
    public function removeSite(array $site)
    {
        $newSites = array();
        foreach ($this->sites as $associatedSite) {
            if ($associatedSite['siteId'] !== $site['siteId']) {
                $newSites[] = $associatedSite;
            }
        }
        $this->sites = $newSites;
    }
}
