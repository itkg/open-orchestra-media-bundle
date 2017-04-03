<?php

namespace OpenOrchestra\Media\Manager;

use OpenOrchestra\Media\Exception\BadFileException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class MediaStorageManager
 */
class MediaStorageManager implements MediaStorageManagerInterface
{
    protected $fileSystem;
    protected $mediaDomain;
    protected $mediaDirectory;

    /**
     * @param Filesystem            $fileSystem
     * @param string                $mediaDomain
     * @param string                $mediaDirectory
     */
    public function __construct(
        Filesystem $fileSystem,
        $mediaDomain,
        $mediaDirectory
    ){
        $this->fileSystem = $fileSystem;
        $this->mediaDomain = $mediaDomain;
        $this->mediaDirectory = $mediaDirectory;
    }

    /**
     * Upload $filePath file with the key $key
     * 
     * @param string  $key
     * @param string  $filePath
     * @param boolean $deleteAfterUpload
     *
     * @throws BadFileException
     */
    public function uploadFile($key, $filePath, $deleteAfterUpload = true)
    {
        if (is_dir($filePath) && false === is_dir($this->mediaDirectory)) {
            throw new BadFileException();
        }
        $targetFile = $this->computePath($key);
        $this->fileSystem->copy($filePath, $targetFile);

        if ($deleteAfterUpload) {
            $this->fileSystem->remove($filePath);
        }
    }

    /**
     * Get content the $key file from storage
     * 
     * @param string $key
     *
     * @return string|boolean if cannot read content
     */
    public function getFileContent($key)
    {
        $path = $this->computePath($key);

        return file_get_contents($path);
    }

    /**
     * Download in $downloadDir the $key file from storage
     * 
     * @param string $key
     * @param string $downloadDir
     * 
     * @return string
     */
    public function downloadFile($key, $downloadDir)
    {
        $targetPath = $downloadDir . DIRECTORY_SEPARATOR . $key;
        $path = $this->computePath($key);
        $this->fileSystem->copy($path, $targetPath);

        return $targetPath;
    }

    /**
     * @param string $key
     */
    public function deleteContent($key)
    {
        $path = $this->computePath($key);
        $this->fileSystem->remove($path);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function exists($key)
    {
        $path = $this->computePath($key);

        return $this->fileSystem->exists($path);
    }

    /**
     * Return the media url
     *
     * @param string $key
     *
     * @return NULL|string
     */
    public function getUrl($key)
    {
        if ($key === null) {

            return null;
        }

        return '//' . $this->mediaDomain . DIRECTORY_SEPARATOR . $key;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function computePath($key)
    {
        return $this->mediaDirectory . DIRECTORY_SEPARATOR . $key;
    }
}
