<?php

namespace OpenOrchestra\Media\Tests\DisplayMedia;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use OpenOrchestra\Media\DisplayMedia\DisplayMediaManager;
use Phake;

/**
 * Class DisplayMediaManagerTest
 */
class DisplayMediaManagerTest extends AbstractBaseTestCase
{
    /**
     * @var DisplayMediaManager
     */
    protected $manager;
    protected $media;
    protected $displayMediaStrategy1;
    protected $displayMediaStrategy2;
    protected $defaultStrategy;
    protected $phakeHtml = array(
        'defaultStrategy' => array(
            'displayPreview' => 'defaultStrategy::displayPreview',
            'renderMedia' => 'defaultStrategy::renderMedia',
            'displayMediaForWysiwyg' => 'defaultStrategy::displayMediaForWysiwyg',
            'getMediaFormatUrl' => 'defaultStrategy::formatUrl'
        ),
        'displayMediaStrategy1' => array(
            'displayPreview' => 'displayMediaStrategy1::displayPreview',
            'renderMedia' => 'displayMediaStrategy1::renderMedia',
            'displayMediaForWysiwyg' => 'displayMediaStrategy1::displayMediaForWysiwyg',
            'getMediaFormatUrl' => 'displayMediaStrategy1::formatUrl'
        )
    );

    /**
     * Set Up
     */
    public function setUp()
    {
        $this->media = Phake::mock('OpenOrchestra\Media\Model\MediaInterface');

        $this->defaultStrategy = Phake::mock('OpenOrchestra\Media\DisplayMedia\DisplayMediaInterface');
        Phake::when($this->defaultStrategy)->support($this->media)->thenReturn(true);
        Phake::when($this->defaultStrategy)->getName()->thenReturn('default');
        Phake::when($this->defaultStrategy)->displayPreview(Phake::anyParameters())
            ->thenReturn($this->phakeHtml['defaultStrategy']['displayPreview']);
        Phake::when($this->defaultStrategy)->renderMedia(Phake::anyParameters())
            ->thenReturn($this->phakeHtml['defaultStrategy']['renderMedia']);
        Phake::when($this->defaultStrategy)->displayMediaForWysiwyg(Phake::anyParameters())
            ->thenReturn($this->phakeHtml['defaultStrategy']['displayMediaForWysiwyg']);
        Phake::when($this->defaultStrategy)->getMediaFormatUrl(Phake::anyParameters())
            ->thenReturn($this->phakeHtml['defaultStrategy']['getMediaFormatUrl']);

        $this->displayMediaStrategy1 = Phake::mock('OpenOrchestra\Media\DisplayMedia\DisplayMediaInterface');
        Phake::when($this->displayMediaStrategy1)->support($this->media)->thenReturn(true);
        Phake::when($this->displayMediaStrategy1)->getName()->thenReturn('phake_strategie_1');
        Phake::when($this->displayMediaStrategy1)->displayPreview(Phake::anyParameters())
            ->thenReturn($this->phakeHtml['displayMediaStrategy1']['displayPreview']);
        Phake::when($this->displayMediaStrategy1)->renderMedia(Phake::anyParameters())
            ->thenReturn($this->phakeHtml['displayMediaStrategy1']['renderMedia']);
        Phake::when($this->displayMediaStrategy1)->displayMediaForWysiwyg(Phake::anyParameters())
            ->thenReturn($this->phakeHtml['displayMediaStrategy1']['displayMediaForWysiwyg']);
        Phake::when($this->displayMediaStrategy1)->getMediaFormatUrl(Phake::anyParameters())
            ->thenReturn($this->phakeHtml['displayMediaStrategy1']['getMediaFormatUrl']);

        $this->displayMediaStrategy2 = Phake::mock('OpenOrchestra\Media\DisplayMedia\DisplayMediaInterface');
        Phake::when($this->displayMediaStrategy2)->support($this->media)->thenReturn(false);
        Phake::when($this->displayMediaStrategy2)->getName()->thenReturn('phake_strategie_2');

        $this->manager = new DisplayMediaManager($this->defaultStrategy);

        $this->manager->addStrategy($this->displayMediaStrategy2);
        $this->manager->addStrategy($this->displayMediaStrategy1);
    }

    /**
     * Test add Strategy
     */
    public function testAddStrategy()
    {
        $fakeStrategy = Phake::mock('OpenOrchestra\Media\DisplayMedia\DisplayMediaInterface');

        $this->manager->addStrategy($fakeStrategy);
    }

    /**
     * Test display Preview
     */
    public function testDisplayPreview()
    {
        $media = $this->manager->displayPreview($this->media);

        $this->assertSupportChain();
        Phake::verify($this->displayMediaStrategy1)->displayPreview($this->media);

        $this->assertSame($this->phakeHtml['displayMediaStrategy1']['displayPreview'], $media);
    }

    /**
     * Test display Preview with default strategy
     */
    public function testDisplayPreviewDefaultStrategy()
    {
        Phake::when($this->displayMediaStrategy1)->support($this->media)->thenReturn(false);
        $media = $this->manager->displayPreview($this->media);

        $this->assertSupportChain();
        Phake::verify($this->defaultStrategy)->displayPreview($this->media);

        $this->assertSame($this->phakeHtml['defaultStrategy']['displayPreview'], $media);
    }

    /**
     * Test render media
     */
    public function testRenderMedia()
    {
        $media = $this->manager->renderMedia($this->media);

        $this->assertSupportChain();
        Phake::verify($this->displayMediaStrategy1)->renderMedia($this->media, array());

        $this->assertSame($this->phakeHtml['displayMediaStrategy1']['renderMedia'], $media);
    }

    /**
     * Test display media  with default strategy
     */
    public function testRenderMediaDefaultStrategy()
    {
        Phake::when($this->displayMediaStrategy1)->support($this->media)->thenReturn(false);
        $media = $this->manager->renderMedia($this->media);

        $this->assertSupportChain();
        Phake::verify($this->defaultStrategy)->renderMedia($this->media, array());

        $this->assertSame($this->phakeHtml['defaultStrategy']['renderMedia'], $media);
    }

    /**
     * Test display media for wysiwyg
     */
    public function testDisplayMediaForWysiwyg()
    {
        $media = $this->manager->displayMediaForWysiwyg($this->media);

        $this->assertSupportChain();
        Phake::verify($this->displayMediaStrategy1)->displayMediaForWysiwyg($this->media, '', '', '');

        $this->assertSame($this->phakeHtml['displayMediaStrategy1']['displayMediaForWysiwyg'], $media);
    }

    /**
     * Test display media wysiwyg with default strategy
     */
    public function testDisplayMediaForWysiwygDefaultStrategy()
    {
        Phake::when($this->displayMediaStrategy1)->support($this->media)->thenReturn(false);
        $media = $this->manager->displayMediaForWysiwyg($this->media);

        $this->assertSupportChain();
        Phake::verify($this->defaultStrategy)->displayMediaForWysiwyg($this->media, '', '', '');

        $this->assertSame($this->phakeHtml['defaultStrategy']['displayMediaForWysiwyg'], $media);
    }

    /**
     * Test get media format url
     */
    public function testGetMediaFormatUrl()
    {
        $media = $this->manager->getMediaFormatUrl($this->media, 'phake_format');

        $this->assertSupportChain();
        Phake::verify($this->displayMediaStrategy1)->getMediaFormatUrl($this->media, 'phake_format');

        $this->assertSame($this->phakeHtml['displayMediaStrategy1']['getMediaFormatUrl'], $media);
    }

    /**
     * Test get media format url with default strategy
     */
    public function testGetMediaFormatUrlDefaultStrategy()
    {
        Phake::when($this->displayMediaStrategy1)->support($this->media)->thenReturn(false);
        $media = $this->manager->getMediaFormatUrl($this->media, 'phake_format');

        $this->assertSupportChain();
        Phake::verify($this->defaultStrategy)->getMediaFormatUrl($this->media, 'phake_format');

        $this->assertSame($this->phakeHtml['defaultStrategy']['getMediaFormatUrl'], $media);
    }

    /**
     * Assert that support is called on strategies 1 & 2 or on all strategies
     */
    protected function assertSupportChain()
    {
        Phake::verify($this->displayMediaStrategy2)->support($this->media);
        Phake::verify($this->displayMediaStrategy1)->support($this->media);
        Phake::verify($this->defaultStrategy, Phake::never())->support($this->media);
    }
}
