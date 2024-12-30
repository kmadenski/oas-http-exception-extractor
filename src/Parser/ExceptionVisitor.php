<?php

declare(strict_types=1);

namespace OasHttpExceptionExtractor\Parser;

use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Throw_;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\NodeVisitorAbstract;

/**
 * AST visitor that collects information about thrown exceptions in class methods
 */
class ExceptionVisitor extends NodeVisitorAbstract
{
    private ?string $currentMethod = null;
    private array $exceptions      = [];
    private ExceptionParser $parser;
    private NameResolver $nameResolver;

    public function __construct(ExceptionParser $parser, NameResolver $nameResolver)
    {
        $this->parser       = $parser;
        $this->nameResolver = $nameResolver;
    }

    public function enterNode(Node $node): ?Node
    {
        if ($node instanceof ClassMethod) {
            $this->handleClassMethodEnter($node);

            return null;
        }

        if ($node instanceof Throw_) {
            $this->handleThrowStatement($node);

            return null;
        }

        return null;
    }

    public function leaveNode(Node $node): ?Node
    {
        if ($node instanceof ClassMethod) {
            $this->currentMethod = null;
        }

        return null;
    }

    /**
     * @return array<string, Name[]>
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    private function handleClassMethodEnter(ClassMethod $node): void
    {
        $this->currentMethod = $node->name->toString();
    }

    private function handleThrowStatement(Throw_ $node): void
    {
        if ($this->currentMethod === null) {
            return;
        }

        if (!$node->expr instanceof New_) {
            return;
        }

        $class = $node->expr->class;
        if (!$class instanceof Name) {
            return;
        }

        $resolvedClass = $this->nameResolver->getNameContext()->getResolvedClassName($class);
        $this->parser->initializeMethod($this->currentMethod);
        $this->parser->addException($this->currentMethod, $resolvedClass->toString());
    }
}
