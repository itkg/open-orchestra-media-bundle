<?php

namespace OpenOrchestra\Media\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class MissingOptionException
 */
class MissingOptionException extends HttpException
{
    /**
     * @param string $message
     */
    public function __construct($option, $method)
    {
        parent::__construct(500, 'The option \'' . $option . '\' is required in ' . $method);
    }
}
