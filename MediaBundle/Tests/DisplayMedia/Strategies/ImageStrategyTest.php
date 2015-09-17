<?php

namespace OpenOrchestra\MediaBundle\Tests\DisplayMedia\Strategies;

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

        $html = '<img src="' . $url .'" alt="' . $alt .'" />';

        $this->assertSame($html, $this->strategy->displayMedia($this->media));
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
     * @param string $image
     * @param string $url
     * @param string $alt
     * @param string $id
     * @param string $format
     *
     * @dataProvider displayImageForWysiwyg
     */
    public function testDisplayMediaForWysiwyg($image, $url, $alt, $id, $format)
    {
        Phake::when($this->media)->getName()->thenReturn($image);
        Phake::when($this->media)->getThumbnail()->thenReturn($image);
        Phake::when($this->media)->getAlt(Phake::anyParameters())->thenReturn($alt);
        Phake::when($this->media)->getId(Phake::anyParameters())->thenReturn($id);
        Phake::when($this->router)->generate(Phake::anyParameters())->thenReturn($this->pathToFile . '/' . $image);

        $html = '<img class="tinymce-media" src="' . $url .'" alt="' . $alt .'" data-id="' . $id . '" data-format="' . $format . '" />';

        $this->assertSame($html, $this->strategy->displayMediaForWysiwyg($this->media, $format));
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
