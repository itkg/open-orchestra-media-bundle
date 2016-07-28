<?php

namespace OpenOrchestra\Media\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class BadOptionFormatException
 */
class BadOptionFormatException extends HttpException
{
    /**
     * BadOptionException constructor.
     *
     * @param string $option
     * @param string $type
     * @param string $method
     */
    public function __construct($option, $type, $method)
    {
        parent::__construct(500, 'The option \'' . $option . '\' must be of type ' . $type . ' in ' . $method);
    }
}
