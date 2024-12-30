<?php

namespace OasHttpExceptionExtractor\Parser;

class MethodExceptions
{

    /**
     * @param string $method
     * @param array<string> $exceptions
     */
    public function __construct(
        public string $method,
        public array  $exceptions
    )
    {
    }

    public static function empty(string $method): self{
        return new self($method, []);
    }
}