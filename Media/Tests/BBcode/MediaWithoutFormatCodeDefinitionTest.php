<?php

namespace OpenOrchestra\Media\Tests\BBcode;

use OpenOrchestra\Media\BBcode\MediaWithoutFormatCodeDefinition;
use OpenOrchestra\Media\Model\MediaInterface;

/**
 * Class MediaWithoutFormatCodeDefinitionTest
 */
class MediaWithoutFormatCodeDefinitionTest extends AbstractMediaCodeDefinitionTest
{
    /**
     * Set up the test
     */
    public function setUp()
    {
        parent::setup();
        $this->definition = new MediaWithoutFormatCodeDefinition($this->repository, $this->displayManager, $this->templating);
    }

    /**
     * Test usesOption
     */
    public function testUsesOption()
    {
        $this->assertSame(false, $this->definition->usesOption());
    }

    /**
     * Provide expected format
     * 
     * @return array
     */
    public function provideFormat()
    {
        return array(
            array($this->format)
        );
    }
}
