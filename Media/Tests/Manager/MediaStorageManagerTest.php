<?php

namespace OpenOrchestra\Media\Tests\Manager;

use OpenOrchestra\Media\Manager\MediaStorageManager;
use Phake;

/**
 * Class MediaStorageManagerTest
 */
class MediaStorageManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var MediaStorageManager */
    protected $mediaStorageManager;
    protected $filesystem;
    protected $mediaDomain = 'domain';
    protected $mediaDirectory = '/fakeDir';

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->filesystem = Phake::mock('Symfony\Component\Filesystem\Filesystem');

        $this->mediaStorageManager =
            new MediaStorageManager(
                $this->filesystem,
                $this->mediaDomain,
                $this->mediaDirectory
            );
    }

    /**
     * @param string $key
     * @param string $filePath
     * @param bool   $delete
     * @param int    $expectedDelete
     *
     * @dataProvider provideKeysAndContents
     */
    public function testUploadFile($key, $filePath, $delete, $expectedDelete)
    {
        $this->mediaStorageManager->uploadFile($key, $filePath, $delete);

        Phake::verify($this->filesystem)->copy($filePath, $this->mediaDirectory."/".$key);
        Phake::verify($this->filesystem, Phake::times($expectedDelete))->remove($filePath);
    }

    /**
     * @return array
     */
    public function provideKeysAndContents()
    {
        return array(
            array('someKey', 'file_path/test', false, 0),
            array('someKey', 'file_path/test', true, 1),
        );
    }

    /**
     * Test uploadFile with no file
     *
     * @param string $key
     * @param string $filePath
     * @param bool   $delete
     *
     * @dataProvider provideKeysAndNoContent
     */
    public function testUploadFileWithNoFile($key, $filePath, $delete)
    {
        $this->expectException('OpenOrchestra\Media\Exception\BadFileException');

        $this->mediaStorageManager->uploadFile($key, $filePath, $delete);
    }

    /**
     * @return array
     */
    public function provideKeysAndNoContent()
    {
        return array(
            array('someKey', './' , false),
            array('someKey', './', true),
        );
    }

    /**
     * @param string $key
     *
     * @dataProvider provideKeys
     */
    public function testDeleteContent($key)
    {
        $this->mediaStorageManager->deleteContent($key);

        Phake::verify($this->filesystem, Phake::times(1))->remove($this->mediaDirectory."/".$key);
    }

    /**
     * @param string $key
     *
     * @dataProvider provideKeys
     */
    public function testExists($key)
    {
        $this->mediaStorageManager->exists($key);

        Phake::verify($this->filesystem, Phake::times(1))->exists($this->mediaDirectory."/".$key);
    }

    /**
     * @return array
     */
    public function provideKeys()
    {
        return array(
            array('someKey')
        );
    }

    /**
     * Test getUrl
     *
     * @param string $key
     * @param string $expectedUrl
     *
     * @dataProvider provideUrl
     */
    public function testGetUrl($key, $expectedUrl)
    {
        $url = $this->mediaStorageManager->getUrl($key);

        $this->assertSame($expectedUrl, $url);
    }

    /**
     * 
     * @return array
     */
    public function provideUrl()
    {
        return array(
            array(null, null),
            array('randomKey', '//' . $this->mediaDomain . '/randomKey'),
        );
    }

    /**
     * Test download file
     */
    public function testDownloadFile()
    {
        $key = 'fakeKey';
        $dir = 'fakeDir';
        $this->mediaStorageManager->downloadFile($key, $dir);
        Phake::verify($this->filesystem)->copy($this->mediaDirectory . DIRECTORY_SEPARATOR . $key ,$dir . DIRECTORY_SEPARATOR . $key);
    }
}
