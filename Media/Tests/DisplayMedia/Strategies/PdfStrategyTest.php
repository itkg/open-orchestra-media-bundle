<?php

namespace OpenOrchestra\Media\Tests\DisplayMedia\Strategies;

use Phake;
use OpenOrchestra\Media\DisplayMedia\Strategies\PdfStrategy;
use OpenOrchestra\Media\Model\MediaInterface;

/**
 * Class PdfStrategyTest
 */
class PdfStrategyTest extends AbstractStrategyTest
{
    /**
     * Set up the test
     */
    public function setUp()
    {
        parent::setUp();

        $this->strategy = new PdfStrategy($this->requestStack, '');
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
            'OpenOrchestraMediaBundle:RenderMedia:pdf.html.twig',
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
            array('test1.pdf', '//' . $this->pathToFile . '/' . 'test1.pdf', 'test1'),
            array('test2.pdf', '//' . $this->pathToFile . '/' . 'test2.pdf', 'test2'),
        );
    }

    /**
     * @return array
     */
    public function getMediaFormatUrl()
    {
        return array(
            array('test1.pdf', MediaInterface::MEDIA_ORIGINAL, '//' . $this->pathToFile . '/test1.pdf'),
            array('test1.pdf', 'max-width', '//' . $this->pathToFile . '/test1.pdf'),
            array('test2.pdf', 'max-height', '//' . $this->pathToFile . '/test2.pdf'),
        );
    }

    /**
     * @param string $mimeType
     * @param bool $supported
     *
     * @dataProvider provideMimeTypes
     */
    public function testSupport($mimeType, $supported)
    {
        Phake::when($this->media)->getMimeType()->thenReturn($mimeType);

        $this->assertSame($supported, $this->strategy->support($this->media));
    }

    /**
     * @return array
     */
    public function provideMimeTypes()
    {
        return array_merge(parent::provideMimeTypes(), array(
            array('application/pdf', true),
            array('video/mpeg', false),
            array('video/quicktime', false),
        ));
    }

    /**
     * test strategy name
     */
    public function testGetName()
    {
        $this->assertSame('pdf', $this->strategy->getName());
    }
}
