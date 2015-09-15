<?php

namespace OpenOrchestra\MediaBundle\Tests\BBcode;

use Phake;
use OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNodeInterface;

/**
 * Class MediaCodeDefinitionTest
 */
abstract class AbstractMediaCodeDefinitionTest extends \PHPUnit_Framework_TestCase
{
    protected $definition;
    protected $repository;
    protected $displayManager;
    protected $mediaNotFoundHtmlTag = '<img src="" alt="Not found">';
    protected $mediaIdOk = 'goodMediaId';
    protected $mediaIdKo = 'badMediaId';
    protected $media;
    protected $format = 'FORMAT';
    protected $BBcodeElementNode;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->media = Phake::mock('OpenOrchestra\Media\Model\MediaInterface');
        $this->repository = Phake::mock('OpenOrchestra\Media\Repository\MediaRepositoryInterface');
        Phake::when($this->repository)->find($this->mediaIdOk)->thenReturn($this->media);
        Phake::when($this->repository)->find($this->mediaIdKo)->thenReturn(null);

        $this->displayManager = Phake::mock('OpenOrchestra\Media\DisplayMedia\DisplayMediaManager');

        $mediaIdNode = Phake::mock('OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNodeInterface');
        Phake::when($mediaIdNode)->getAsBBCode()->thenReturn($this->mediaIdOk);

        $this->BBcodeElementNode = Phake::mock('OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNodeInterface');
        Phake::when($this->BBcodeElementNode)->getChildren()->thenReturn(array(0 => $mediaIdNode));
        Phake::when($this->BBcodeElementNode)->getAttribute()->thenReturn(array('media' => $this->format));
    }

    /**
     * Test usesOption
     */
    abstract public function testUsesOption();

    /**
     * @param BBcodeElementNodeInterface $el
     * 
     * @dataProvider provideBBNodeWithBadMediaId
     */
    public function testGetHtmlWithBadMediaId(BBcodeElementNodeInterface $el)
    {
        $html = $this->definition->getHtml($el);

        $this->assertSame($this->mediaNotFoundHtmlTag, $html);
    }

    /**
     * Provide BBcodeElementNode with no/bad mediaId
     */
    public function provideBBNodeWithBadMediaId()
    {
        $nodeWithNoMediaId = Phake::mock('OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNodeInterface');
        Phake::when($nodeWithNoMediaId)->getChildren()->thenReturn(array());

        $mediaIdNode = Phake::mock('OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNodeInterface');
        Phake::when($mediaIdNode)->getAsBBcode()->thenReturn($this->mediaIdKo);

        $nodeWithBadMediaId = Phake::mock('OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNodeInterface');
        Phake::when($nodeWithBadMediaId)->getChildren()->thenReturn(array(0 => $mediaIdNode));

        return array(
            array($nodeWithNoMediaId),
            array($nodeWithBadMediaId)
        );
    }

    /**
     * @param BBcodeElementNodeInterface $el
     * 
     * @dataProvider provideBBNodeWithBadMediaId
     */
    public function testGetPreviewHtmlWithBadMediaId(BBcodeElementNodeInterface $el)
    {
        $html = $this->definition->getPreviewHtml($el);

        $this->assertSame($this->mediaNotFoundHtmlTag, $html);
    }

    /**
     * @param string $expectedFormat
     * 
     * @dataProvider provideFormat
     */
    public function testGetHtmlWithGoodMediaId($expectedFormat)
    {
        $html = $this->definition->getHtml($this->BBcodeElementNode);

        Phake::verify($this->displayManager)->displayMedia($this->media, $expectedFormat);
    }

    /**
     * Provide expected format
     */
    abstract public function provideFormat();

    /**
     * @param string $expectedFormat
     * 
     * @dataProvider provideFormat
     */
    public function testGetPreviewHtmlWithGoodMediaId($expectedFormat)
    {
        $html = $this->definition->getPreviewHtml($this->BBcodeElementNode);

        Phake::verify($this->displayManager)->displayMediaForWysiwyg($this->media, $expectedFormat);
    }
}