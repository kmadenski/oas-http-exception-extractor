<?php

namespace OasHttpExceptionExtractor\Parser\Visitor;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class ExceptionResolver extends NodeVisitorAbstract
{
    /**
     * @var string|null The name of the current method being traversed.
     */
    private $currentMethod = null;

    /**
     * @var array An associative array mapping method names to arrays of exception class names.
     */
    private $exceptions = [];

    /**
     * Called when entering a node during traversal.
     *
     * @param Node $node The current node.
     */
    public function enterNode(Node $node)
    {
        // Detect entering a class method
        if ($node instanceof Node\Stmt\ClassMethod) {
            $this->currentMethod = $node->name->toString();
            // Uncomment the line below for debugging
            // echo "Entering method: {$this->currentMethod}\n";
        }

        // Detect throw statements
        if ($node instanceof Node\Expr\Throw_) {
            $throwExpr = $node->expr;

            // Ensure that the throw expression is a 'new' instantiation
            if ($throwExpr instanceof Node\Expr\New_) {
                $class = $throwExpr->class;

                // Get the class name as it appears in the code
                if ($class instanceof Node\Name) {
                    // Initialize the exceptions array for the current method if not already
                    if (!isset($this->exceptions[$this->currentMethod])) {
                        $this->exceptions[$this->currentMethod] = [];
                    }

                    // Add the exception class to the current method's list
                    $this->exceptions[$this->currentMethod][] = $class;
                }
            }
        }
    }

    /**
     * Called when leaving a node during traversal.
     *
     * @param Node $node The current node.
     */
    public function leaveNode(Node $node)
    {
        // Detect leaving a class method
        if ($node instanceof Node\Stmt\ClassMethod) {
            // Uncomment the line below for debugging
            // echo "Leaving method: {$this->currentMethod}\n";
            $this->currentMethod = null;
        }
    }

    /**
     * Retrieves the collected exceptions.
     *
     * @return array Associative array with method names as keys and arrays of exception class names as values.
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }
}
