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
 *
 * @ODM\Indexes({
 *  @ODM\Index(keys={"parent"="asc", "siteId"="asc"}),
 * })
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
     * @ODM\Field(type="hash")
     */
    protected $names = array();

    /**
     * @var FolderInterface
     *
     * @ODM\ReferenceOne(targetDocument="OpenOrchestra\Media\Model\FolderInterface", inversedBy="subFolders")
     */
    protected $parent;

    /**
     * @var string $path
     *
     * @ODM\Field(type="string")
     */
    protected $path;

    /**
     * @var ArrayCollection
     *
     * @ODM\ReferenceMany(targetDocument="OpenOrchestra\Media\Model\FolderInterface", mappedBy="parent")
     */
    protected $subFolders;

    /**
     * @var string
     *
     * @ODM\Field(type="string")
     */
    protected $siteId;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subFolders = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param array $names
     */
    public function setNames(array $names)
    {
        foreach ($names as $language => $name) {
            $this->addName($language, $name);
        }
    }

    /**
     * @return array
     */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * @param string $language
     *
     * @return string
     */
    public function getName($language)
    {
        if (isset($this->names[$language])) {
            return $this->names[$language];
        }

        return '';
    }

    /**
     * @param string $language
     * @param string $name
     */
    public function addName($language, $name)
    {
        if (is_string($language) && is_string($name)) {
            $this->names[$language] = $name;
        }
    }

    /**
     * @param string $language
     */
    public function removeName($language)
    {
        if (is_string($language) && isset($this->names[$language])) {
            unset($this->names[$language]);
        }
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
     * Set path
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get path
     *
     * @return string $path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return Collection
     */
    public function getSubFolders()
    {
        return $this->subFolders;
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
}
