<?php
namespace OasHttpExceptionExtractor\Parser;

use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\Error;
use PhpParser\NodeTraverser;

abstract class AbstractParser
{
    protected Parser $parser;
    protected NodeTraverser $traverser;
    protected array $results = [];

    public function __construct()
    {
        // Initialize the PHP parser
        $this->parser = (new ParserFactory())->createForHostVersion();

        // Initialize the NodeTraverser
        $this->traverser = new NodeTraverser();

        // Add common visitors if any (e.g., NameResolver)
        $this->traverser->addVisitor(new NameResolver());
    }

    /**
     * Parses the given PHP code.
     *
     * @param string $code The PHP code to parse.
     * @return array The results collected by the visitor.
     * @throws Error If parsing fails.
     */
    public function parse(string $code): array
    {
        $this->results = []; // Reset previous data

        // Parse the PHP code into an AST
        $ast = $this->parser->parse($code);

        if ($ast === null) {
            throw new Error("Failed to parse the code.");
        }

        // Traverse the AST
        $this->traverser->traverse($ast);

        return $this->results;
    }

    /**
     * Get the collected results.
     *
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }
}
