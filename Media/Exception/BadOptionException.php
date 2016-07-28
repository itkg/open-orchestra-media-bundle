<?php

namespace OpenOrchestra\Media\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class BadOptionException
 */
class BadOptionException extends HttpException
{
    /**
     * BadOptionException constructor.
     *
     * @param string $badOption
     * @param array  $goodOptions
     * @param string $method
     */
    public function __construct($badOption, array $goodOptions, $method)
    {
        parent::__construct(500, 'The option \'' . $badOption . '\' is not valid in ' . $method . '.'
            . ' You can only use the following options: ' . implode(', ', $goodOptions));
    }
}
