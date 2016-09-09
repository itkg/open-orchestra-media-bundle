<?php

namespace OpenOrchestra\MediaModelBundle\Validator\Constraints;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class MediaFileValidator
 */
class MediaFileValidator extends ConstraintValidator
{
    protected $mimeTypes;

    /**
     * @param array  $mimeTypes
     */
    public function __construct(array $mimeTypes)
    {
        $this->mimeTypes = $mimeTypes;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param UploadedFile   $file      The value that should be validated
     * @param Constraint     $constraint The constraint for the validation
     */
    public function validate($file, Constraint $constraint)
    {
        if (!in_array($file->getMimeType(), $this->mimeTypes)) {
            $this->context->buildViolation($constraint->messageMimeTypes)
                ->atPath('file')
                ->addViolation();
        }
        if ($file->getSize() > $file->getMaxFilesize()) {
            $this->context->buildViolation($constraint->messageMaxSize, array('%max_size%' => $file->getMaxFilesize()))
                ->atPath('file')
                ->addViolation();
        }
    }
}
