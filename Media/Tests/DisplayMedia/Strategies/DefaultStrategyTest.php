<?php

namespace OpenOrchestra\Media\Tests\DisplayMedia\Strategies;

use OpenOrchestra\Media\DisplayMedia\Strategies\DefaultStrategy;
use Phake;
use OpenOrchestra\Media\Model\MediaInterface;

/**
 * Class DefaultStrategyTest
 */
class DefaultStrategyTest extends AbstractStrategyTest
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
     *
     * @dataProvider displayImage
     */
    public function testDisplayMedia($image, $url, $alt)
    {
        parent::testDisplayMedia($image, $url, $alt);

        Phake::when($this->media)->getName()->thenReturn($image);

        $this->strategy->displayMedia($this->media);

        Phake::verify($this->templating)->render(
            'OpenOrchestraMediaBundle:RenderMedia:default.html.twig',
            array(
                'media_url' => $url,
                'media_name' => $image,
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
            array('test1.txt', '//' . $this->pathToFile . '/' . 'test1.txt', 'test1'),
            array('test2.txt', '//' . $this->pathToFile . '/' . 'test2.txt', 'test2'),
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
    public function provideMimeTypes()
    {
        return array(
            array('application/pdf', true),
            array('video/mpeg', true),
            array('video/quicktime', true),
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
