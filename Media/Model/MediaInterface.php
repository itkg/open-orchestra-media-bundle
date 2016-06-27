<?php

namespace OpenOrchestra\Media\Model;

use Doctrine\Common\Collections\Collection;
use OpenOrchestra\ModelInterface\Model\KeywordableInterface;
use OpenOrchestra\ModelInterface\Model\TranslatedValueContainerInterface;
use OpenOrchestra\ModelInterface\Model\TranslatedValueInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use OpenOrchestra\ModelInterface\Model\BlameableInterface;
use OpenOrchestra\ModelInterface\Model\TimestampableInterface;

/**
 * Interface MediaInterface
 */
interface MediaInterface extends TimestampableInterface, BlameableInterface, KeywordableInterface, TranslatedValueContainerInterface
{
    const MEDIA_ORIGINAL = 'original';

    /**
     * @return string
     */
    public function getFilesystemName();

    /**
     * @param string $filesystemName
     */
    public function setFilesystemName($filesystemName);

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
     * @return MediaFolderInterface
     */
    public function getMediaFolder();

    /**
     * @param MediaFolderInterface $folder
     */
    public function setMediaFolder(MediaFolderInterface $folder);

    /**
     * @return UploadedFile
     */
    public function getFile();

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file);

    /**
     * @return string
     */
    public function getMediaType();

    /**
     * @param string $mediaType
     */
    public function setMediaType($mediaType);

        /**
     * @return string
     */
    public function getMimeType();

    /**
     * @param string $mimeType
     */
    public function setMimeType($mimeType);

    /**
     * @return string
     */
    public function getThumbnail();

    /**
     * @param string $thumbnail
     */
    public function setThumbnail($thumbnail);

    /**
     * @param string $language
     *
     * @return string
     */
    public function getAlt($language = 'en');

    /**
     * @return Collection
     */
    public function getAlts();

    /**
     * @return string
     */
    public function getComment();

    /**
     * @param string $comment
     */
    public function setComment($comment);

    /**
     * @return string
     */
    public function getCopyright();

    /**
     * @param string $copyright
     */
    public function setCopyright($copyright);

    /**
     * @return Collection
     */
    public function getTitles();

    /**
     * @param string $language
     *
     * @return string
     */
    public function getTitle($language = 'en');

    /**
     * @param string $reference
     */
    public function addUsageReference($reference);

    /**
     * @param string $reference
     */
    public function removeUsageReference($reference);

    /**
     * @return array
     */
    public function getUsageReference();

    /**
     * @param string $formatName
     * @param string $alternativeName
     */
    public function addAlternative($formatName, $alternativeName);

    /**
     * @param string $formatName
     */
    public function removeAlternative($formatName);

    /**
     * @return array
     */
    public function getAlternatives();

    /**
     * @param $formatName
     *
     * @return string|null
     */
    public function getAlternative($formatName);

    /**
     * @return bool
     */
    public function isDeletable();

    /**
     * @param TranslatedValueInterface $alt
     */
    public function addAlt(TranslatedValueInterface $alt);

    /**
     * @param TranslatedValueInterface $alt
     */
    public function removeAlt(TranslatedValueInterface $alt);

    /**
     * @param TranslatedValueInterface $title
     */
    public function addTitle(TranslatedValueInterface $title);

    /**
     * @param TranslatedValueInterface $title
     */
    public function removeTitle(TranslatedValueInterface $title);
}
