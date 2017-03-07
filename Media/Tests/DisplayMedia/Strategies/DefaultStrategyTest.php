<?php

namespace OpenOrchestra\Media\Tests\DisplayMedia\Strategies;

use OpenOrchestra\Media\DisplayMedia\Strategies\DefaultStrategy;
use Phake;
use OpenOrchestra\Media\Model\MediaInterface;

/**
 * Class DefaultStrategyTest
 */
class DefaultStrategyTest extends AbstractDisplayMediaStrategyTest
{
    /**
     * Set up the test
     */
    public function setUp()
    {
        parent::setUp();

        $this->strategy = new DefaultStrategy($this->requestStack);
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
     *
     * @dataProvider displayImage
     */
    public function testRenderMedia($image, $url, $alt, $id = '', $class = '', $style = '')
    {
        Phake::when($this->media)->getName()->thenReturn($image);

        parent::testRenderMedia($image, $url, $alt);

        $this->strategy->renderMedia($this->media, array(
            'id' => $id,
            'class' => $class,
            'style' => $style
        ));

        Phake::verify($this->templating)->render(
            'OpenOrchestraMediaBundle:RenderMedia:default.html.twig',
            array(
                'media_url' => $url,
                'media_name' => $image,
                'id' => $id,
                'class' => $class,
                'style' => $style
            )
        );
    }

    /**
     * @return array
     */
    public function displayImage()
    {
        return array(
            'withoutOptions' => array('test1.txt', '//' . $this->pathToFile . '/' . 'test1.txt', 'test1'),
            'withOptions' => array('test2.txt', '//' . $this->pathToFile . '/' . 'test2.txt', 'test2', 'test2', 'id', 'class', 'style'),
        );
    }

    /**
     * @return array
     */
    public function getMediaFormatUrl()
    {
        return array(
            array('test1.text', MediaInterface::MEDIA_ORIGINAL, '//' . $this->pathToFile . '/test1.text'),
            array('test1.text', 'max-width', '//' . $this->pathToFile . '/test1.text'),
            array('test2.text', 'max-height', '//' . $this->pathToFile . '/test2.text'),
        );
    }

    /**
     * @return array
     */
    public function provideMediaTypes()
    {
        return array(
            'image' => array('image', true),
            'audio' => array('audio', true),
            'video' => array('video', true),
            'pdf' => array('pdf', true),
            'default' => array('default', true),
        );
    }

    /**
     * test strategy name
     */
    public function testGetName()
    {
        $this->assertSame('default', $this->strategy->getName());
    }
}
