<?php
namespace OasHttpExceptionExtractor\Parser;

use OasHttpExceptionExtractor\Parser\Visitor\ExceptionResolver;

class ExceptionParser extends AbstractParser
{
    // Arrays to store exceptions
    private array $allExceptions = [];
    private array $httpExceptions = [];

    public function __construct()
    {
        parent::__construct();

        // Add the ExceptionVisitor to the traverser
        $this->traverser->addVisitor(new ExceptionResolver($this));
    }

    /**
     * Initializes tracking for a new method.
     *
     * @param string $methodName
     */
    public function initializeMethod(string $methodName): void
    {
        if (!isset($this->allExceptions[$methodName])) {
            $this->allExceptions[$methodName] = [];
        }

        if (!isset($this->httpExceptions[$methodName])) {
            $this->httpExceptions[$methodName] = [];
        }
    }

    /**
     * Adds an exception to the appropriate lists.
     *
     * @param string $methodName
     * @param string $exceptionClass
     */
    public function addException(string $methodName, string $exceptionClass): void
    {
        // Add to allExceptions
        if (!in_array($exceptionClass, $this->allExceptions[$methodName], true)) {
            $this->allExceptions[$methodName][] = $exceptionClass;
        }

        // Check if it's a Symfony HttpException
        if ($this->isHttpException($exceptionClass)) {
            if (!in_array($exceptionClass, $this->httpExceptions[$methodName], true)) {
                $this->httpExceptions[$methodName][] = $exceptionClass;
            }
        }
    }

    /**
     * Determines if the given exception class extends Symfony's HttpException.
     *
     * @param string $exceptionClass
     * @return bool
     */
    private function isHttpException(string $exceptionClass): bool
    {
        // Define the base Symfony HttpException class
        $symfonyHttpExceptionBase = '\\Symfony\\Component\\HttpKernel\\Exception\\HttpException';

        // Use reflection to determine inheritance if possible
        if (class_exists($exceptionClass) && class_exists($symfonyHttpExceptionBase)) {
            return is_subclass_of($exceptionClass, $symfonyHttpExceptionBase);
        }

        // If classes do not exist (e.g., parsing without autoloading), use naming conventions
        // For example, check if the class name ends with 'HttpException' or belongs to Symfony's namespace
        return strpos($exceptionClass, '\\Symfony\\Component\\HttpKernel\\Exception\\') === 0 &&
            substr($exceptionClass, -14) === 'HttpException';
    }

    /**
     * Parses the given PHP code and extracts exceptions thrown in each method.
     *
     * @param string $code PHP code to parse.
     * @return array Associative array with method names as keys and arrays of exception classes as values.
     *               Includes both allExceptions and httpExceptions.
     * @throws Error If parsing fails.
     */
    public function parse(string $code): array
    {
        parent::parse($code);

        return [
            'allExceptions' => $this->allExceptions,
            'httpExceptions' => $this->httpExceptions,
        ];
    }

    /**
     * Gets the collected all exceptions.
     *
     * @return array
     */
    public function getAllExceptions(): array
    {
        return $this->allExceptions;
    }

    /**
     * Gets the collected HTTP exceptions.
     *
     * @return array
     */
    public function getHttpExceptions(): array
    {
        return $this->httpExceptions;
    }
}
