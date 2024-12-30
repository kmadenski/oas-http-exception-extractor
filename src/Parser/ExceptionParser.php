<?php

declare(strict_types=1);

namespace OasHttpExceptionExtractor\Parser;

use PhpParser\Error;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Parser that extracts exception information from PHP code
 */
class ExceptionParser
{
    private Parser $parser;
    private NodeTraverser $traverser;
    private NameResolver $nameResolver;

    /** @var array<string, string[]> Map of method names to their thrown exceptions */
    private array $allExceptions = [];

    /** @var array<string, string[]> Map of method names to their HTTP exceptions */
    private array $httpExceptions = [];

    public function __construct()
    {
        $this->parser       = (new ParserFactory())->createForHostVersion();
        $this->traverser    = new NodeTraverser();
        $this->nameResolver = new NameResolver();

        // NameResolver must be added first to resolve names before our visitor
        $this->traverser->addVisitor($this->nameResolver);
        $this->traverser->addVisitor(new ExceptionVisitor($this, $this->nameResolver));
    }

    /**
     * Initialize exception tracking for a method
     */
    public function initializeMethod(string $methodName): void
    {
        $this->allExceptions[$methodName]  ??= [];
        $this->httpExceptions[$methodName] ??= [];
    }

    /**
     * Add an exception to the appropriate collections
     */
    public function addException(string $methodName, string $exceptionClass): void
    {
        if (!in_array($exceptionClass, $this->allExceptions[$methodName], true)) {
            $this->allExceptions[$methodName][] = $exceptionClass;
        }

        if ($this->isHttpException($exceptionClass)) {
            if (!in_array($exceptionClass, $this->httpExceptions[$methodName], true)) {
                $this->httpExceptions[$methodName][] = $exceptionClass;
            }
        }
    }

    /**
     * Check if the given class is a Symfony HTTP exception
     */
    private function isHttpException(string $exceptionClass): bool
    {
        if (class_exists($exceptionClass) && class_exists(HttpException::class)) {
            return is_subclass_of($exceptionClass, HttpException::class);
        }

        return str_starts_with($exceptionClass, '\\Symfony\\Component\\HttpKernel\\Exception\\')
            && str_ends_with($exceptionClass, 'HttpException');
    }

    /**
     * Parse PHP code and extract exceptions
     *
     * @return array{
     *     allExceptions: array<string, string[]>,
     *     httpExceptions: array<string, string[]>
     * }
     * @throws Error If parsing fails or code is invalid
     */
    public function parse(string $code): array
    {
        $ast = $this->parser->parse($code);
        if (!is_array($ast)) {
            throw new Error('Failed to parse the code into an AST');
        }

        /** @var Node[] $ast */
        $this->traverser->traverse($ast);

        return [
            'allExceptions'  => $this->allExceptions,
            'httpExceptions' => $this->httpExceptions,
        ];
    }

    /**
     * @return array<string, string[]>
     */
    public function getAllExceptions(): array
    {
        return $this->allExceptions;
    }

    /**
     * @return array<string, string[]>
     */
    public function getHttpExceptions(): array
    {
        return $this->httpExceptions;
    }
}
