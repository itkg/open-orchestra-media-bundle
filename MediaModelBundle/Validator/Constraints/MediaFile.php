<?php

namespace OpenOrchestra\MediaModelBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class MediaFile
 */
class MediaFile extends Constraint
{
    public $messageMimeTypes = 'open_orchestra_media_model.field.file.mime_types';
    public $messageMaxSize = 'open_orchestra_media_model.field.file.max_size';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'media_file';
    }
}
