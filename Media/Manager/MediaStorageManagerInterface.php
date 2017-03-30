<?php

namespace OpenOrchestra\Media\Manager;

use OpenOrchestra\Media\Exception\BadFileException;

/**
 * Class MediaStorageManager
 */
interface MediaStorageManagerInterface
{
    /**
     * Upload $filePath file with the key $key
     * 
     * @param string  $key
     * @param string  $filePath
     * @param boolean $deleteAfterUpload
     *
     * @throws BadFileException
     */
    public function uploadFile($key, $filePath, $deleteAfterUpload = true);

    /**
     * Get content the $key file from storage
     * 
     * @param string $key
     *
     * @return string|boolean if cannot read content
     */
    public function getFileContent($key);

    /**
     * Download in $downloadDir the $key file from storage
     * 
     * @param string $key
     * @param string $downloadDir
     * 
     * @return string
     */
    public function downloadFile($key, $downloadDir);

    /**
     * @param string $key
     */
    public function deleteContent($key);
    /**
     * @param string $key
     *
     * @return bool
     */
    public function exists($key);

    /**
     * Return the media url
     *
     * @param string $key
     *
     * @return NULL|string
     */
    public function getUrl($key);
}
