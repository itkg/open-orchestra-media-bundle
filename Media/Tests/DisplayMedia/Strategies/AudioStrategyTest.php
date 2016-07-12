<?php

namespace OpenOrchestra\Media\Tests\DisplayMedia\Strategies;

use Phake;
use OpenOrchestra\Media\DisplayMedia\Strategies\AudioStrategy;

/**
 * Class AudioStrategyTest
 */
class AudioStrategyTest extends AbstractStrategyTest
{
    /**
     * Set up the test
     */
    public function setUp()
    {
        parent::setUp();

        $this->strategy = new AudioStrategy($this->requestStack);
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
        $mimeType = 'Mime Type';
        parent::testDisplayMedia($image, $url, $alt);
        Phake::when($this->media)->getMimeType(Phake::anyParameters())->thenReturn($mimeType);

        $this->strategy->displayMedia($this->media);

        Phake::verify($this->templating)->render(
            'OpenOrchestraMediaBundle:RenderMedia:audio.html.twig',
            array(
                'media_url' => $url,
                'media_type' => $mimeType,
                'style' => '',
            )
        );
    }

    /**
     * @return array
     */
    public function displayImage()
    {
        return array(
            array('test1.mp3', '//' . $this->pathToFile . '/test1.mp3', 'test1'),
            array('test2.ogg', '//' . $this->pathToFile . '/test2.ogg', 'test2'),
        );
    }

    /**
     * @param string $image
     * @param string $url
     * @param string $alt
     * @param string $id
     * @param string $format
     *
     * @dataProvider displayImage
     */
    public function testDisplayMediaForWysiwyg($image, $url, $alt, $id = null, $format = null)
    {
        Phake::when($this->media)->getId(Phake::anyParameters())->thenReturn($image);
        $format = 'preview';

        $this->strategy->displayMediaForWysiwyg($this->media);

        Phake::verify($this->templating)->render(
            'OpenOrchestraMediaBundle:BBcode/WysiwygDisplay:audio.html.twig',
            array(
                'media_id' => $image,
                'style' => '',
            )
        );
    }

    /**
     * @return array
     */
    public function getMediaFormatUrl()
    {
        return array(
            array('test1.mp3', '', '//' . $this->pathToFile . '/test1.mp3'),
            array('test2.ogg', '', '//' . $this->pathToFile . '/test2.ogg'),
        );
    }

    /**
     * @return array
     */
    public function provideMimeTypes()
    {
        return array_merge(
            parent::provideMimeTypes(),
            array(
                array('audio/mpeg', true),
                array('audio/mp3', true),
            )
        );
    }

    /**
     * test strategy name
     */
    public function testGetName()
    {
        $this->assertSame('audio', $this->strategy->getName());
    }
}
