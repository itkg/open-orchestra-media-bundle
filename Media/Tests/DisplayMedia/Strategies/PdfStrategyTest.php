<?php

namespace OpenOrchestra\Media\Tests\DisplayMedia\Strategies;

use Phake;
use OpenOrchestra\Media\DisplayMedia\Strategies\PdfStrategy;
use OpenOrchestra\Media\Model\MediaInterface;

/**
 * Class PdfStrategyTest
 */
class PdfStrategyTest extends AbstractDisplayMediaStrategyTest
{
    /**
     * Set up the test
     */
    public function setUp()
    {
        parent::setUp();

        $this->strategy = new PdfStrategy('');
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
            'OpenOrchestraMediaBundle:RenderMedia:pdf.html.twig',
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
            'withoutOptions' => array('test1.pdf', '//' . $this->pathToFile . '/' . 'test1.pdf', 'test1'),
            'withOptions' => array('test2.pdf', '//' . $this->pathToFile . '/' . 'test2.pdf', 'test2', 'id', 'class', 'style'),
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
     * @return array
     */
    public function provideMediaTypes()
    {
        return array_merge(
            parent::provideMediaTypes(),
            array('pdf' => array('pdf', true))
        );
    }

    /**
     * test strategy name
     */
    public function testGetName()
    {
        $this->assertSame('pdf', $this->strategy->getName());
    }
}
