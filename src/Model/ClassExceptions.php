<?php

declare(strict_types=1);

namespace OasHttpExceptionExtractor\Model;

/**
 * Collection of exceptions thrown by methods in a class
 */
class ClassExceptions
{
    /** @var MethodExceptions[] */
    private array $methods = [];

    /**
     * Add exceptions for a method
     */
    public function addMethodException(MethodExceptions $methodExceptions): self
    {
        $this->methods[] = $methodExceptions;

        return $this;
    }

    /**
     * Get exceptions for a specific method
     */
    public function getMethodExceptions(string $methodName): MethodExceptions
    {
        foreach ($this->methods as $methodException) {
            if ($methodException->method === $methodName) {
                return $methodException;
            }
        }

        return MethodExceptions::empty($methodName);
    }

    /**
     * Get all method exceptions
     * 
     * @return MethodExceptions[]
     */
    public function getAllMethodExceptions(): array
    {
        return $this->methods;
    }

    /**
     * Check if any methods have exceptions
     */
    public function hasExceptions(): bool
    {
        return !empty($this->methods);
    }
}
