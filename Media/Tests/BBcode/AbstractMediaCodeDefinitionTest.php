<?php

namespace OpenOrchestra\Media\Tests\BBcode;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use Phake;
use OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNode;

/**
 * Class MediaCodeDefinitionTest
 */
abstract class AbstractMediaCodeDefinitionTest extends AbstractBaseTestCase
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
    protected $templating;

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

        $this->BBcodeElementNode = Phake::mock('OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNode');
        Phake::when($this->BBcodeElementNode)->getChildren()->thenReturn(array(0 => $mediaIdNode));
        Phake::when($this->BBcodeElementNode)->getAttribute()->thenReturn(array('media' => '{"format":"'.$this->format.'"}'));

        $this->templating = Phake::mock('Symfony\Component\Templating\EngineInterface');
        Phake::when($this->templating)->render(Phake::anyParameters())->thenReturn($this->mediaNotFoundHtmlTag);
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
    public function testGetHtmlWithBadMediaId(BBcodeElementNode $el)
    {
        $html = $this->definition->getHtml($el);

        $this->assertSame($this->mediaNotFoundHtmlTag, $html);
    }

    /**
     * Provide BBcodeElementNode with no/bad mediaId
     */
    public function provideBBNodeWithBadMediaId()
    {
        $nodeWithNoMediaId = Phake::mock('OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNode');
        Phake::when($nodeWithNoMediaId)->getChildren()->thenReturn(array());

        $mediaIdNode = Phake::mock('OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNode');
        Phake::when($mediaIdNode)->getAsBBcode()->thenReturn($this->mediaIdKo);

        $nodeWithBadMediaId = Phake::mock('OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNode');
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
    public function testGetPreviewHtmlWithBadMediaId(BBcodeElementNode $el)
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

        Phake::verify($this->displayManager)->renderMedia($this->media, array('format' => $expectedFormat, 'style' => ''));
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

        Phake::verify($this->displayManager)->displayMediaForWysiwyg($this->media, $expectedFormat, '', '');
    }
}
