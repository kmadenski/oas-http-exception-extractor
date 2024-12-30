<?php

namespace OasHttpExceptionExtractor;

use OasHttpExceptionExtractor\Parser\MethodExceptionsDTO;
use OasHttpExceptionExtractor\Parser\Visitor\ExceptionResolver;
use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;

class ExceptionExtractor
{
    public function __construct(
        private readonly array $sources
    )
    {
    }

    public function extract(): array
    {
        $result = [];
        foreach ($this->sources as $source) {
            $fileToParse = $source;

            if (!file_exists($fileToParse)) {
                echo "File not found: {$fileToParse}\n";
                exit(1);
            }

            $code = file_get_contents($fileToParse);

            $parser = (new ParserFactory())->createForHostVersion();
            $traverser = new NodeTraverser();

            $nameResolver = new NameResolver();
            $exceptionResolver = new ExceptionResolver();

            $traverser->addVisitor($nameResolver);
            $traverser->addVisitor($exceptionResolver);

            try {
                $ast = $parser->parse($code);
            } catch (Error $e) {
                echo 'Parse Error: ', $e->getMessage();
                //@todo wtf?
                exit(1);
            }

            $traverser->traverse($ast);
            $exceptions = $exceptionResolver->getExceptions();
            $nameContext = $nameResolver->getNameContext();
            foreach ($exceptions as $method => $methodExceptions) {
                $founded = [];
                foreach ($methodExceptions as $exception) {
                    $className = $nameContext->getResolvedClassName($exception);
                    if (class_exists($className->name)) {
                        $isSubclass = is_subclass_of($className->name, \Symfony\Component\HttpKernel\Exception\HttpException::class);
                        if ($isSubclass) {
                            $founded[] = $className->name;
                        }
                    }
                }
                if(!empty($founded)){
                    $result[] = new MethodExceptionsDTO($source, $method, $founded);
                }
            }

        }

        return $result;
    }
}