<?php

namespace OasHttpExceptionExtractor\Parser;

class ExceptionsDTO
{

    /**
     * @param string $source
     * @param string $method
     * @param array<string> $exceptions
     */
    public function __construct(
        public string $source,
        public string $method,
        public array  $exceptions
    )
    {
    }
}