<?php

namespace OpenOrchestra\Media\Model;

use OpenOrchestra\ModelInterface\Model\KeywordableInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use OpenOrchestra\ModelInterface\Model\BlameableInterface;
use OpenOrchestra\ModelInterface\Model\TimestampableInterface;
use OpenOrchestra\ModelInterface\Model\UseTrackableInterface;

/**
 * Interface MediaInterface
 */
interface MediaInterface extends TimestampableInterface, BlameableInterface, KeywordableInterface, UseTrackableInterface
{
    const ENTITY_TYPE = 'media';
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
    public function getAlt($language);

    /**
     * @param array $alts
     */
    public function setAlts(array $alts);

    /**
     * @return array
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
     * @return array
     */
    public function getTitles();

    /**
     * @param string $language
     *
     * @return string
     */
    public function getTitle($language);

    /**
     * @param array $titles
     */
    public function setTitles(array $titles);

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
     * @param string $language
     * @param string $alt
     */
    public function addAlt($language, $alt);

    /**
     * @param string $language
     */
    public function removeAlt($language);

    /**
     * @param string $language
     * @param string $title
     */
    public function addTitle($language, $title);

    /**
     * @param string $language
     */
    public function removeTitle($language);

    /**
     * @return array
     */
    public function getMediaInformations();

    /**
     * @param array $mediaInformations
     */
    public function setMediaInformations($mediaInformations);

    /**
     * @param string $informationName
     * @param string $value
     */
    public function addMediaInformation($informationName, $value);

    /**
     * @param string $informationName
     *
     * @return string|null
     */
    public function getMediaInformation($informationName);
}
