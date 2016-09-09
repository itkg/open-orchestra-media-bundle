<?php

namespace OpenOrchestra\ModelBundle\Tests\Validator\Constraints;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use OpenOrchestra\MediaModelBundle\Validator\Constraints\MediaFile;
use OpenOrchestra\MediaModelBundle\Validator\Constraints\MediaFileValidator;
use Phake;

/**
 * Class CheckRoutePatternValidatorTest
 */
class MediaFileValidatorTest extends AbstractBaseTestCase
{
    /**
     * @var MediaFileValidator
     */
    protected $validator;

    protected $maxSize = 1000;
    protected $mimeTypes = 1000;
    protected $context;
    protected $constraint;
    protected $constraintViolationBuilder;
    protected $file;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->constraint = new MediaFile();
        $this->context = Phake::mock('Symfony\Component\Validator\Context\ExecutionContextInterface');
        $this->constraintViolationBuilder = Phake::mock('Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface');

        Phake::when($this->context)->buildViolation(Phake::anyParameters())->thenReturn($this->constraintViolationBuilder);
        Phake::when($this->constraintViolationBuilder)->atPath(Phake::anyParameters())->thenReturn($this->constraintViolationBuilder);

        $this->file = Phake::mock('Symfony\Component\HttpFoundation\File\UploadedFile');
        $this->mimeTypes = array('fakeMimeType');

        $this->validator = new MediaFileValidator($this->mimeTypes);
        $this->validator->initialize($this->context);
    }

    /**
     * Test instanc
     */
    public function testClass()
    {
        $this->assertInstanceOf('Symfony\Component\Validator\ConstraintValidator', $this->validator);
    }

    /**
     * @param string $mimeType
     * @param int    $size
     * @param int    $violationTimes
     *
     * @dataProvider provideCountAndViolation
     */
    public function testAddViolationOrNot($mimeType, $size, $violationTimes)
    {
        Phake::when($this->file)->getMimeType()->thenReturn($mimeType);
        Phake::when($this->file)->getSize()->thenReturn($size);
        Phake::whenStatic($this->file)->getMaxFilesize()->thenReturn($this->maxSize);

        $this->validator->validate($this->file, $this->constraint);

        Phake::verify($this->context, Phake::times($violationTimes))->buildViolation(Phake::anyParameters());
        Phake::verify($this->constraintViolationBuilder, Phake::times($violationTimes))->atPath('file');
    }

    /**
     * @return array
     */
    public function provideCountAndViolation()
    {
        return array(
            array('fakeMimeType', 5, 0),
            array('otherMimeType', 5, 1),
            array('otherMimeType', 58000, 2),
            array('fakeMimeType', 58000, 1),
        );
    }
}
