<?php

namespace OpenOrchestra\MediaModelBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Blameable\Traits\BlameableDocument;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use OpenOrchestra\Media\Model\MediaFolderInterface;
use OpenOrchestra\Media\Model\MediaInterface;
use OpenOrchestra\MongoTrait\Keywordable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use OpenOrchestra\MongoTrait\UseTrackable;

/**
 * Class Media
 *
 * @ODM\Document(
 *   collection="media",
 *   repositoryClass="OpenOrchestra\MediaModelBundle\Repository\MediaRepository"
 * )
 */
class Media implements MediaInterface
{
    use BlameableDocument;
    use TimestampableDocument;
    use Keywordable;
    use UseTrackable;

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
     * @var string
     *
     * @ODM\Field(type="string")
     */
    protected $siteId;

    /**
     * @var string $filesystemName
     *
     * @ODM\Field(type="string")
     */
    protected $filesystemName;

    /**
     * @var string $mediaType
     *
     * @ODM\Field(type="string")
     */
    protected $mediaType;

    /**
     * @var string $mimeType
     *
     * @ODM\Field(type="string")
     */
    protected $mimeType;

    /**
     * @var MediaFolderInterface
     *
     * @ODM\ReferenceOne(targetDocument="OpenOrchestra\Media\Model\MediaFolderInterface", inversedBy="medias")
     */
    protected $mediaFolder;

    /**
     * @var UploadedFile
     */
    protected $file;

    /**
     * @var string $thumbnail
     *
     * @ODM\Field(type="string")
     */
    protected $thumbnail;

    /**
     * @ODM\Field(type="hash")
     */
    protected $titles = array();

    /**
     * @var string
     *
     * @ODM\Field(type="string")
     */
    protected $copyright;

    /**
     * @ODM\Field(type="hash")
     */
    protected $alternatives = array();

    /**
     * @ODM\Field(type="hash")
     */
    protected $mediaInformations = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->keywords = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getFilesystemName()
    {
        return $this->filesystemName;
    }

    /**
     * @param string $filesystemName
     */
    public function setFilesystemName($filesystemName)
    {
        $this->filesystemName = $filesystemName;
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
     * @return MediaFolderInterface
     */
    public function getMediaFolder()
    {
        return $this->mediaFolder;
    }

    /**
     * @param MediaFolderInterface $mediaFolder
     */
    public function setMediaFolder(MediaFolderInterface $mediaFolder)
    {
        $this->mediaFolder = $mediaFolder;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * @param string $mediaType
     */
    public function setMediaType($mediaType)
    {
        $this->mediaType = $mediaType;
    }

        /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }

    /**
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param string $thumbnail
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * @return string
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * @param string $copyright
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;
    }

    /**
     * @param array $titles
     */
    public function setTitles(array $titles)
    {
        foreach ($titles as $language => $title) {
            $this->addTitle($language, $title);
        }
    }

    /**
     * @return array
     */
    public function getTitles()
    {
        return $this->titles;
    }

    /**
     * @param string $language
     *
     * @return string
     */
    public function getTitle($language)
    {
        if (isset($this->titles[$language])) {
            return $this->titles[$language];
        }

        return '';
    }

    /**
     * @param string $language
     * @param string $title
     */
    public function addTitle($language, $title)
    {
        if (is_string($language) && is_string($title)) {
            $this->titles[$language] = $title;
        }
    }

    /**
     * @param string $language
     */
    public function removeTitle($language)
    {
        if (is_string($language) && isset($this->titles[$language])) {
            unset($this->titles[$language]);
        }
    }

    /**
     * @param string $formatName
     * @param string $alternativeName
     */
    public function addAlternative($formatName, $alternativeName)
    {
        $this->alternatives[$formatName] = $alternativeName;
    }

    /**
     * @param string $formatName
     */
    public function removeAlternative($formatName)
    {
        if (isset($this->alternatives[$formatName])) {
            unset($this->alternatives[$formatName]);
        }
    }

    /**
     * @return array
     */
    public function getAlternatives()
    {
        return $this->alternatives;
    }

    /**
     * @param $formatName
     *
     * @return string|null
     */
    public function getAlternative($formatName)
    {
        if (isset($this->alternatives[$formatName])) {
            return $this->alternatives[$formatName];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getMediaInformations()
    {
        return $this->mediaInformations;
    }

    /**
     * @param mixed $mediaInformations
     */
    public function setMediaInformations($mediaInformations)
    {
        $this->mediaInformations = $mediaInformations;
    }

    /**
     * @param string $informationName
     * @param string $value
     */
    public function addMediaInformation($informationName, $value)
    {
        $this->mediaInformations[$informationName] = $value;
    }

    /**
     * @param string $informationName
     *
     * @return string|null
     */
    public function getMediaInformation($informationName)
    {
        if (isset($this->mediaInformations[$informationName])) {
            return $this->mediaInformations[$informationName];
        }

        return null;
    }
}
