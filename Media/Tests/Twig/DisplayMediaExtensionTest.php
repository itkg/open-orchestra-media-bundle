<?php

namespace OpenOrchestra\Media\Tests\Twig;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use Phake;
use OpenOrchestra\Media\Twig\DisplayMediaExtension;

/**
 * Class DisplayMediaExtensionTest
 */
class DisplayMediaExtensionTest extends AbstractBaseTestCase
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
        $this->media = Phake::mock('OpenOrchestra\Media\Model\MediaInterface');

        $this->extension = new DisplayMediaExtension(
            $this->displayMediaManager,
            $this->mediaRepository,
            $this->requestStack
        );
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
        $this->assertCount(4, $this->extension->getFunctions());
    }

    /**
     * Test mediaMymType
     *
     * @param string $mediaId
     *
     * @dataProvider provideMediaId
     */
    public function testRenderMedia($mediaId)
    {
        $method = 'renderMedia';
        $html = '<img src="test" alt="test">';
        $this->displayMediaTest($mediaId, $html, $method);
    }

    /**
     * @return array
     */
    public function provideMediaId()
    {
        return array(
            array('mediaId')
        );
    }

    /**
     * Test getMediaUrl
     *
     * @param string $mediaId
     *
     * @dataProvider provideMediaId
     */
    public function testGetMediaUrl($mediaId)
    {
        $format = 'format';
        Phake::when($this->mediaRepository)->find(Phake::anyParameters())->thenReturn($this->media);

        $this->extension->getMediaUrl($mediaId, $format);

        Phake::verify($this->displayMediaManager, Phake::times(1))->getMediaFormatUrl($this->media, $format);
    }

    /**
     * @param $mediaId
     * @param $methodReturn
     * @param $method
     */
    protected function displayMediaTest($mediaId, $methodReturn, $method)
    {
        Phake::when($this->mediaRepository)->find(Phake::anyParameters())->thenReturn($this->media);
        Phake::when($this->displayMediaManager)->displayPreview($this->media)->thenReturn($methodReturn);
        Phake::when($this->displayMediaManager)->renderMedia($this->media, array())->thenReturn($methodReturn);

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
        Phake::when($this->mediaRepository)->find(Phake::anyParameters())->thenReturn($this->media);

        $result = $this->extension->getMediaAlt($mediaId);

        $this->assertSame('', $result);
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
