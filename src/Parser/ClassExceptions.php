<?php

namespace OasHttpExceptionExtractor\Parser;

class ClassExceptions
{
    /**
     * @var array<MethodExceptions>
     */
    private array $methods = [];

    public function addMethodException(MethodExceptions $methodExceptionsDTO): self
    {
        $this->methods[] = $methodExceptionsDTO;
        return $this;
    }

    /**
     * @param string $methodName
     * @return MethodExceptions
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

}