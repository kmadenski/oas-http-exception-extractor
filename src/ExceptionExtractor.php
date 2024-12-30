<?php

declare(strict_types=1);

namespace OasHttpExceptionExtractor;

use OasHttpExceptionExtractor\Model\ClassExceptions;
use OasHttpExceptionExtractor\Model\MethodExceptions;
use OasHttpExceptionExtractor\Parser\ExceptionParser;
use RuntimeException;

/**
 * Extracts HTTP exceptions from PHP files
 */
class ExceptionExtractor
{
    private ExceptionParser $parser;

    /**
     * Extract HTTP exceptions from a PHP file
     * 
     * @throws RuntimeException If file does not exist or cannot be read
     */
    public function extract(string $fileToParse): ClassExceptions
    {
        $this->parser = new ExceptionParser();

        $this->validateFile($fileToParse);
        $code = $this->readFile($fileToParse);
        $parseResult = $this->parseCode($code);
        
        return $this->buildResult($parseResult['httpExceptions']);
    }

    /**
     * Validate that the file exists and is readable
     * 
     * @throws RuntimeException If file does not exist
     */
    private function validateFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new RuntimeException("File not found: {$filePath}");
        }
    }

    /**
     * Read the contents of a file
     * 
     * @throws RuntimeException If file cannot be read
     */
    private function readFile(string $filePath): string
    {
        $code = file_get_contents($filePath);
        if ($code === false) {
            throw new RuntimeException("Failed to read file: {$filePath}");
        }

        return $code;
    }

    /**
     * Parse PHP code and extract exceptions
     * 
     * @return array{httpExceptions: array<string, string[]>}
     */
    private function parseCode(string $code): array
    {
        return $this->parser->parse($code);
    }

    /**
     * Build ClassExceptions result from parsed exceptions
     * 
     * @param array<string, string[]> $httpExceptions
     */
    private function buildResult(array $httpExceptions): ClassExceptions
    {
        $result = new ClassExceptions();
        
        foreach ($httpExceptions as $method => $exceptions) {
            if (!empty($exceptions)) {
                $result->addMethodException(new MethodExceptions($method, $exceptions));
            }
        }

        return $result;
    }
}
