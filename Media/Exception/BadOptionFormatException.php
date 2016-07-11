<?php

namespace OpenOrchestra\Media\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class BadOptionFormatException
 */
class BadOptionFormatException extends HttpException
{
    /**
     * @param string $message
     */
    public function __construct($option, $type, $method)
    {
        parent::__construct(500, 'The option \'' . $option . '\' must be of type ' . $type . ' in ' . $method);
    }
}
