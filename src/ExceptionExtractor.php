<?php

namespace OasHttpExceptionExtractor;

use OasHttpExceptionExtractor\Parser\ClassExceptions;
use OasHttpExceptionExtractor\Parser\MethodExceptions;
use OasHttpExceptionExtractor\Parser\Visitor\ExceptionResolver;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;

class ExceptionExtractor
{
    public function extract(string $fileToParse): ClassExceptions
    {
        $result = new ClassExceptions();

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

        $ast = $parser->parse($code);

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
                $result->addMethodException(new MethodExceptions($method, $founded));
            }
        }
        return $result;
    }
}