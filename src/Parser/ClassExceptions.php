<?php

namespace OasHttpExceptionExtractor\Parser;

class ClassExceptions
{
    /**
     * @var array<MethodExceptionsDTO>
     */
    private array $methods = [];

    public function addMethodException(MethodExceptionsDTO $methodExceptionsDTO): self
    {
        $this->methods[] = $methodExceptionsDTO;
        return $this;
    }

    /**
     * @param string $methodName
     * @return MethodExceptionsDTO
     * @throws \RuntimeException
     */
    public function getMethodExceptions(string $methodName): MethodExceptionsDTO
    {
        foreach ($this->methods as $methodException) {
            if ($methodException->method === $methodName) {
                return $methodException;
            }
        }

        throw new \RuntimeException("No method exceptions found for method: {$methodName}");
    }

}