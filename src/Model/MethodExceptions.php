<?php

declare(strict_types=1);

namespace OasHttpExceptionExtractor\Model;

/**
 * Data transfer object for method exceptions
 */
class MethodExceptions
{
    /**
     * @param string $method Method name
     * @param string[] $exceptions List of exception class names
     */
    public function __construct(
        public readonly string $method,
        public readonly array $exceptions
    ) {
    }

    /**
     * Create an empty instance for a method
     */
    public static function empty(string $method): self
    {
        return new self($method, []);
    }

    /**
     * Check if method has any exceptions
     */
    public function hasExceptions(): bool
    {
        return !empty($this->exceptions);
    }

    /**
     * Get number of exceptions
     */
    public function getExceptionCount(): int
    {
        return count($this->exceptions);
    }
}
