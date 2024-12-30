<?php

namespace OasHttpExceptionExtractor\Tests\examples;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MyClass
{
    public function methodOne()
    {
        throw new \InvalidArgumentException('Invalid argument provided.');
    }

    public function methodTwo()
    {
        // This method does not throw any exceptions.
    }

    public function methodThree()
    {
        if ($this->somethingWrong()) {
            throw new \RuntimeException('Runtime error occurred.');
        }
    }

    public function methodFour()
    {
        throw new NotFoundHttpException('Resource not found.');
    }

    public function methodFive()
    {
        throw new AccessDeniedHttpException('Access denied.');
    }

    private function somethingWrong()
    {
        // Placeholder for some condition
        return true;
    }
}
