<?php

namespace OpenOrchestra\Media\Tests\DisplayMedia\Strategies;

use Phake;
use OpenOrchestra\Media\DisplayMedia\Strategies\VideoStrategy;
use OpenOrchestra\Media\Model\MediaInterface;

/**
 * Class VideoStrategyTest
 */
class VideoStrategyTest extends AbstractDisplayMediaStrategyTest
{
    protected $translator;
    protected $translation = 'Some Translation';

    /**
     * Set up the test
     */
    public function setUp()
    {
        parent::setUp();

        $this->translator = Phake::mock('Symfony\Component\Translation\TranslatorInterface');
        Phake::when($this->translator)->trans(Phake::anyParameters())->thenReturn($this->translation);
        $this->strategy = new VideoStrategy('', $this->translator);
        $this->strategy->setContainer($this->container);
        $this->strategy->setRouter($this->router);
    }

    /**
     * @param string $image
     * @param string $url
     * @param string $alt
     * @param string $id
     * @param string $class
     * @param string $style
     * @param int    $width
     * @param int    $height
     *
     * @dataProvider displayImage
     */
    public function testRenderMedia($image, $url, $alt, $id = '', $class = '', $style = '', $width = 0, $height = 0)
    {
        $mimeType = 'Mime Type';
        parent::testRenderMedia($image, $url, $alt);
        Phake::when($this->media)->getMimeType(Phake::anyParameters())->thenReturn($mimeType);

        $this->strategy->renderMedia($this->media, array(
            'id' => $id,
            'class' => $class,
            'style' => $style,
            'width' => $width,
            'height' => $height
        ));

        Phake::verify($this->templating)->render(
            'OpenOrchestraMediaBundle:RenderMedia:video.html.twig',
            array(
                'media_url' => $url,
                'media_type' => $mimeType,
                'id' => $id,
                'class' => $class,
                'style' => $style,
                'width' => $width,
                'height' => $height
            )
        );
    }

    /**
     * @return array
     */
    public function displayImage()
    {
        return array(
            'withoutOptions' => array('test1.mp4', '//' . $this->pathToFile . '/' . 'test1.mp4', 'test1'),
            'withOptions' => array('test2.avi', '//' . $this->pathToFile . '/' . 'test2.avi', 'test2', 'id', 'class', 'style', 320, 240),
        );
    }

    /**
     * @return array
     */
    public function getMediaFormatUrl()
    {
        return array(
            array('test1.mp4', MediaInterface::MEDIA_ORIGINAL, '//' . $this->pathToFile . '/test1.mp4'),
            array('test1.mp4', 'max-width', '//' . $this->pathToFile . '/test1.mp4'),
            array('test2.avi', 'max-height', '//' . $this->pathToFile . '/test2.avi'),
        );
    }

    /**
     * @return array
     */
    public function provideMediaTypes()
    {
        return array_merge(
            parent::provideMediaTypes(),
            array('video' => array('video', true)
        ));
    }

    /**
     * test strategy name
     */
    public function testGetName()
    {
        $this->assertSame('video', $this->strategy->getName());
    }
}
