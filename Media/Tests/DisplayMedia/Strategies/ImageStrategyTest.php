<?php

namespace OpenOrchestra\Media\Tests\DisplayMedia\Strategies;

use Phake;
use OpenOrchestra\Media\DisplayMedia\Strategies\ImageStrategy;
use OpenOrchestra\Media\Model\MediaInterface;

/**
 * Class ImageStrategyTest
 */
class ImageStrategyTest extends AbstractStrategyTest
{
    /**
     * Set up the test
     */
    public function setUp()
    {
        parent::setUp();

        $this->strategy = new ImageStrategy($this->requestStack, '');
        $this->strategy->setContainer($this->container);
        $this->strategy->setRouter($this->router);
    }

    /**
     * @param string $image
     * @param string $url
     * @param string $alt
     *
     * @dataProvider displayImage
     */
    public function testDisplayMedia($image, $url, $alt)
    {
        parent::testDisplayMedia($image, $url, $alt);

        Phake::when($this->media)->getFilesystemName()->thenReturn($image);
        Phake::when($this->media)->getAlt(Phake::anyParameters())->thenReturn($alt);
        $format = 'preview';

        $this->strategy->displayMedia($this->media);

        Phake::verify($this->templating)->render(
            'OpenOrchestraMediaBundle:DisplayMedia/FullDisplay:image.html.twig',
            array(
                'media_url' => $url,
                'media_alt' => $alt
            )
        );
    }

    /**
     * @return array
     */
    public function displayImage()
    {
        return array(
            array('test1.jpg', '//' . $this->pathToFile . '/' . 'test1.jpg', 'test1'),
            array('test2.png', '//' . $this->pathToFile . '/' . 'test2.png', 'test2'),
        );
    }

    /**
     * @param string $image
     * @param string $url
     * @param string $alt
     * @param string $id
     * @param string $format
     *
     * @dataProvider displayImageForWysiwyg
     */
    public function testDisplayMediaForWysiwyg($image, $url, $alt, $id = null, $format = null)
    {
        Phake::when($this->media)->getId()->thenReturn($id);
        Phake::when($this->media)->getFilesystemName()->thenReturn($image);
        Phake::when($this->media)->getAlt(Phake::anyParameters())->thenReturn($alt);
        Phake::when($this->router)->generate(Phake::anyParameters())->thenReturn($this->pathToFile . '/' . $image);

        $this->strategy->displayMediaForWysiwyg($this->media, $format);

        Phake::verify($this->templating)->render(
            'OpenOrchestraMediaBundle:BBcode/WysiwygDisplay:image.html.twig',
            array(
                'media_url' => $url,
                'media_alt' => $alt,
                'media_id' => $id,
                'media_format' => $format
            )
        );
    }

    /**
     * @return array
     */
    public function displayImageForWysiwyg()
    {
        return array(
            array('test1.jpg', '//' . $this->pathToFile . '/' . 'test1.jpg', 'test1', 'id1', 'original'),
            array('test2.png', '//' . $this->pathToFile . '/' . 'test2.png', 'test2', 'id2', 'rectangle'),
        );
    }

    /**
     * @return array
     */
    public function getMediaFormatUrl()
    {
        return array(
            array('test1.jpg', MediaInterface::MEDIA_ORIGINAL, '//' . $this->pathToFile . '/' . 'test1.jpg'),
            array('test1.jpg', 'max-width', '//' . $this->pathToFile . '/' . 'max-width-test1.jpg'),
            array('test2.png', 'max-height', '//' . $this->pathToFile . '/' . 'max-height-test2.png'),
        );
    }

    /**
     * @param string $image
     * @param string $format
     * @param string $url
     *
     * @dataProvider getMediaFormatUrl
     */
    public function testGetMediaFormatUrl($image, $format, $url)
    {
        Phake::when($this->media)->getThumbnail()->thenReturn($image);
        Phake::when($this->router)->generate(Phake::anyParameters())->thenReturn(
            (MediaInterface::MEDIA_ORIGINAL == $format) ?
                $this->pathToFile . '/' . $image :
                $this->pathToFile . '/' . $format. '-' . $image
        );

        $this->assertSame($url, $this->strategy->getMediaFormatUrl($this->media, $format));
    }

    /**
     * @return array
     */
    public function provideMimeTypes()
    {
        return array(
            array('image/jpeg', true),
            array('image/gif', true),
            array('image/png', true),
            array('application/pdf', false),
            array('video/mpeg', false),
            array('video/quicktime', false),
            array('text/csv', false),
            array('text/html', false),
            array('text/plain', false),
            array('audio/mpeg', false),
            array('application/msword', false),
        );
    }

    /**
     * test strategy name
     */
    public function testGetName()
    {
        $this->assertSame('image', $this->strategy->getName());
    }
}
