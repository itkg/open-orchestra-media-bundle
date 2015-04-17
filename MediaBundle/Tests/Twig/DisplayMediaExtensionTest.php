<?php

namespace OpenOrchestra\MediaBundle\Tests\Twig;

use OpenOrchestra\ModelInterface\Model\TranslatedValueInterface;
use Phake;
use OpenOrchestra\Media\Model\MediaInterface;
use OpenOrchestra\MediaBundle\Twig\DisplayMediaExtension;

/**
 * Class DisplayMediaExtensionTest
 */
class DisplayMediaExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DisplayMediaExtension
     */
    protected $extension;

    protected $displayMediaManager;
    protected $mediaRepository;
    protected $noMedia = '';
    protected $media;
    protected $requestStack;
    protected $request;
    protected $language;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->request = Phake::mock('Symfony\Component\HttpFoundation\Request');
        $this->requestStack = Phake::mock('Symfony\Component\HttpFoundation\RequestStack');
        Phake::when($this->requestStack)->getMasterRequest()->thenReturn($this->request);
        $this->displayMediaManager = Phake::mock('OpenOrchestra\Media\DisplayMedia\DisplayMediaManager');
        $this->mediaRepository = Phake::mock('OpenOrchestra\Media\Repository\MediaRepositoryInterface');
        $this->media = Phake::mock('OpenOrchestra\MediaBundle\Document\Media');

        $this->extension = new DisplayMediaExtension($this->displayMediaManager, $this->mediaRepository, $this->requestStack);
    }

    /**
     * Test name
     */
    public function testGetName()
    {
        $this->assertSame('media', $this->extension->getName());
    }

    /**
     * Test functions
     */
    public function testFunctions()
    {
        $this->assertCount(5, $this->extension->getFunctions());
    }

    /**
     * Test mediaMymType
     *
     * @param string $mediaId
     *
     * @dataProvider provideMediaId
     */
    public function testDisplayMedia($mediaId)
    {
        $method = 'displayMedia';
        $html = '<img src="test" alt="test">';
        $this->displayMediaOrPreviewTest($mediaId, $html, $method);
    }

    /**
     * @return array
     */
    public function provideMediaId()
    {
        return array(
            array('mediaId'),
            array(MediaInterface::MEDIA_PREFIX . 'mediaId')
        );
    }

    /**
     * Test mediaMymType
     *
     * @param string $mediaId
     *
     * @dataProvider provideMediaId
     */
    public function testDisplayMediaNull($mediaId)
    {
        Phake::when($this->mediaRepository)->find(Phake::anyParameters())->thenReturn(null);

        $this->assertSame('', $this->extension->displayMedia($mediaId));

        Phake::verify($this->displayMediaManager, Phake::never())->displayMedia($this->media);
    }

    /**
     * Test mediaMymType
     *
     * @param string $mediaId
     *
     * @dataProvider provideMediaId
     */
    public function testMediaPreview($mediaId)
    {
        $method = 'mediaPreview';
        $url = 'test.jpg';
        $this->displayMediaOrPreviewTest($mediaId, $url, $method);
    }

    /**
     * Test mediaMymType
     *
     * @param string $mediaId
     *
     * @dataProvider provideMediaId
     */
    public function testMediaPreviewNull($mediaId)
    {
        Phake::when($this->mediaRepository)->find(Phake::anyParameters())->thenReturn(null);

        $this->assertSame($this->noMedia, $this->extension->mediaPreview($mediaId));

        Phake::verify($this->displayMediaManager, Phake::never())->displayPreview($this->media);
    }

    /**
     * Test getMediaFormatUrl
     *
     * @param string $mediaId
     *
     * @dataProvider provideMediaId
     */
    public function testGetMediaFormatUrl($mediaId)
    {
        $format = 'format';
        Phake::when($this->mediaRepository)->find(Phake::anyParameters())->thenReturn($this->media);

        $this->extension->getMediaFormatUrl($mediaId, $format);

        Phake::verify($this->displayMediaManager, Phake::times(1))->getMediaFormatUrl($this->media, $format);
    }

    /**
     * @param $mediaId
     * @param $methodReturn
     * @param $method
     */
    protected function displayMediaOrPreviewTest($mediaId, $methodReturn, $method)
    {
        Phake::when($this->mediaRepository)->find(Phake::anyParameters())->thenReturn($this->media);
        Phake::when($this->displayMediaManager)->displayPreview($this->media)->thenReturn($methodReturn);
        Phake::when($this->displayMediaManager)->displayMedia($this->media)->thenReturn($methodReturn);

        $this->assertSame($methodReturn, $this->extension->$method($mediaId));
    }

    /**
     * @param string $mediaId
     * @param string $language
     * @param string $value
     *
     * @dataProvider provideMediaAlt
     */
    public function testGetMediaAlt($mediaId, $language, $value)
    {
        Phake::when($this->request)->get(Phake::anyParameters())->thenReturn($language);
        Phake::when($this->media)->getAlt($language)->thenReturn($value);
        Phake::when($this->mediaRepository)->find(Phake::anyParameters())->thenReturn($this->media);

        $result = $this->extension->getMediaAlt($mediaId);

        $this->assertSame($value, $result);
    }

    /**
     * @return array
     */
    public function provideMediaAlt()
    {
        return array(
            array('mediaId', 'en', 'alten'),
            array('mediaId', 'fr', 'altfr'),
        );
    }

    /**
     * @param string $mediaId
     * @param string $language
     * @param string $value
     *
     * @dataProvider provideMediaTitle
     */
    public function testGetMediaTitle($mediaId, $language, $value)
    {
        Phake::when($this->request)->get(Phake::anyParameters())->thenReturn($language);
        Phake::when($this->media)->getTitle($language)->thenReturn($value);
        Phake::when($this->mediaRepository)->find(Phake::anyParameters())->thenReturn($this->media);

        $result = $this->extension->getMediaTitle($mediaId);

        $this->assertSame($value, $result);
    }

    /**
     * @return array
     */
    public function provideMediaTitle()
    {
        return array(
            array('mediaId', 'en', 'titleen'),
            array('mediaId', 'fr', 'titlefr'),
        );
    }
}
