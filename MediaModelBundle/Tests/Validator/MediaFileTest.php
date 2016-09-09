<?php

namespace OpenOrchestra\MediaModelBundle\Tests\Validator;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use OpenOrchestra\MediaModelBundle\Validator\Constraints\MediaFile;

/**
 * Class MediaFileTest
 */
class MediaFileTest extends AbstractBaseTestCase
{

    protected $constraint;

    /**
     * Set Up
     */
    public function setUp()
    {
      $this->constraint = new MediaFile();
    }

    /**
     * Test constraint
     */
    public function testConstraint()
    {
        $this->assertInstanceOf('Symfony\Component\Validator\Constraint', $this->constraint);
        $this->assertSame('media_file', $this->constraint->validatedBy());
        $this->assertSame('open_orchestra_media_model.field.file.mime_types', $this->constraint->messageMimeTypes);
        $this->assertSame('open_orchestra_media_model.field.file.max_size', $this->constraint->messageMaxSize);
    }
}
