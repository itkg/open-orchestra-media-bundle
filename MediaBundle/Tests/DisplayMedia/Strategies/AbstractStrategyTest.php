<?php

namespace OpenOrchestra\MediaBundle\Tests\DisplayMedia\Strategies;

use Phake;

/**
 * Class AbstractStrategyTest
 */
abstract class AbstractStrategyTest extends \PHPUnit_Framework_TestCase
{
    protected $media;
    protected $router;
    protected $container;
    protected $request;
    protected $strategy;
    protected $requestStack;
    protected $locale = 'en';
    protected $pathToFile = 'pathToFile';

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->request = Phake::mock('Symfony\Component\HttpFoundation\Request');
        Phake::when($this->request)->getLocale()->thenReturn($this->locale);
        $this->requestStack = Phake::mock('Symfony\Component\HttpFoundation\RequestStack');
        Phake::when($this->requestStack)->getMasterRequest()->thenReturn($this->request);
        $this->media = Phake::mock('OpenOrchestra\Media\Model\MediaInterface');
        $this->router = Phake::mock('Symfony\Component\Routing\Router');

        $this->container = Phake::mock('Symfony\Component\DependencyInjection\ContainerInterface');
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
        Phake::when($this->media)->getName()->thenReturn($image);
        Phake::when($this->media)->getThumbnail()->thenReturn($image);
        Phake::when($this->media)->getAlt(Phake::anyParameters())->thenReturn($alt);
        Phake::when($this->router)->generate(Phake::anyParameters())->thenReturn($this->pathToFile . '/' . $image);
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
        Phake::when($this->media)->getName()->thenReturn($image);
        Phake::when($this->media)->getThumbnail()->thenReturn($image);
        Phake::when($this->media)->getAlt(Phake::anyParameters())->thenReturn($alt);
        Phake::when($this->media)->getId(Phake::anyParameters())->thenReturn($image);
        Phake::when($this->router)->generate(Phake::anyParameters())->thenReturn($this->pathToFile . '/' . $image);
        $format = 'preview';

        $html = '<img class="tinymce-media" src="' . $url . '" alt="'
            . $alt . '" data-id="' . $image . '" />';

        $this->assertSame($html, $this->strategy->displayMediaForWysiwyg($this->media, $format));
    }

    /**
     * @param string $image
     * @param string $url
     *
     * @dataProvider displayImage
     */
    public function testDisplayPreview($image, $url)
    {
        Phake::when($this->media)->getThumbnail()->thenReturn($image);
        Phake::when($this->router)->generate(Phake::anyParameters())->thenReturn($this->pathToFile . '/' . $image);

        $this->assertSame($url, $this->strategy->displayPreview($this->media));
    }

    /**
     * @return array
     */
    abstract public function displayImage();

    /**
     * @param string $image
     * @param string $format
     * @param string $url
     *
     * @dataProvider getMediaFormatUrl
     */
    public function testGetMediaFormatUrl($image, $format, $url)
    {
        Phake::when($this->media)->getName()->thenReturn($image);
        Phake::when($this->media)->getThumbnail()->thenReturn($image);
        Phake::when($this->router)->generate(Phake::anyParameters())->thenReturn($this->pathToFile . '/' . $image);

        $this->assertSame($url, $this->strategy->getMediaFormatUrl($this->media, $format));
    }

    /**
     * @return array
     */
    abstract public function getMediaFormatUrl();

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
        return array(
            array('image/jpeg', false),
            array('image/gif', false),
            array('image/png', false),
            array('text/csv', false),
            array('text/html', false),
            array('text/plain', false),
            array('application/msword', false),
        );
    }
}
