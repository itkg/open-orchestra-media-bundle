<?php

namespace OpenOrchestra\MediaModelBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Blameable\Traits\BlameableDocument;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use OpenOrchestra\Media\Model\MediaFolderInterface;
use OpenOrchestra\Media\Model\MediaInterface;
use OpenOrchestra\ModelInterface\Model\TranslatedValueInterface;
use OpenOrchestra\MongoTrait\Keywordable;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * @ODM\EmbedMany(targetDocument="OpenOrchestra\ModelInterface\Model\TranslatedValueInterface")
     */
    protected $titles;

    /**
     * @ODM\EmbedMany(targetDocument="OpenOrchestra\ModelInterface\Model\TranslatedValueInterface")
     */
    protected $alts;

    /**
     * @var string
     *
     * @ODM\Field(type="string")
     */
    protected $copyright;

    /**
     * @var string
     *
     * @ODM\Field(type="string")
     */
    protected $comment;

    /**
     * @ODM\Field(type="collection")
     */
    protected $usageReference = array();

    /**
     * @ODM\Field(type="hash")
     */
    protected $alternatives = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->keywords = new ArrayCollection();
        $this->titles = new ArrayCollection();
        $this->alts = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getAlts()
    {
        return $this->alts;
    }

    /**
     * @param string $language
     *
     * @return string
     */
    public function getAlt($language = 'en')
    {
        return $this->getChosenLanguage($this->alts, $language);
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
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
     * @return ArrayCollection
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
    public function getTitle($language = 'en')
    {
        return $this->getChosenLanguage($this->titles, $language);
    }

    /**
     * @param string $reference
     */
    public function addUsageReference($reference)
    {
        if (!in_array($reference, $this->usageReference)) {
            $this->usageReference[] = $reference;
        }
    }

    /**
     * @param string $reference
     */
    public function removeUsageReference($reference)
    {
        foreach ($this->usageReference as $key => $usageReference) {
            if ($usageReference == $reference) {
                unset($this->usageReference[$key]);
            }
        }
    }

    /**
     * @return array
     */
    public function getUsageReference()
    {
        return $this->usageReference;
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
     * @return bool
     */
    public function isDeletable()
    {
        return !(bool) count($this->usageReference);
    }

    /**
     * @param TranslatedValueInterface $alt
     */
    public function addAlt(TranslatedValueInterface $alt)
    {
        $this->alts->add($alt);
    }

    /**
     * @param TranslatedValueInterface $alt
     */
    public function removeAlt(TranslatedValueInterface $alt)
    {
        $this->alts->removeElement($alt);
    }

    /**
     * @param TranslatedValueInterface $title
     */
    public function addTitle(TranslatedValueInterface $title)
    {
        $this->titles->add($title);
    }

    /**
     * @param TranslatedValueInterface $title
     */
    public function removeTitle(TranslatedValueInterface $title)
    {
        $this->titles->removeElement($title);
    }

    /**
     * @return array
     */
    public function getTranslatedProperties()
    {
        return array('getTitles', 'getAlts');
    }

    /**
     * @param ArrayCollection $mixed
     * @param string          $language
     *
     * @return string
     */
    protected function getChosenLanguage($mixed, $language)
    {
        $choosenLanguage = $mixed->filter(function(TranslatedValueInterface $translatedValue) use ($language) {
            return $language == $translatedValue->getLanguage();
        });

        if ($choosenLanguage->isEmpty()) {
            return '';
        }

        return $choosenLanguage->first()->getValue();
    }
}
