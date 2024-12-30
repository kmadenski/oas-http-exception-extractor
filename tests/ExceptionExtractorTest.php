<?php

declare(strict_types=1);

namespace OasHttpExceptionExtractor\Tests;

use OasHttpExceptionExtractor\ExceptionExtractor;
use OasHttpExceptionExtractor\Model\ClassExceptions;
use OasHttpExceptionExtractor\Model\MethodExceptions;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionExtractorTest extends TestCase
{
    private ExceptionExtractor $extractor;

    protected function setUp(): void
    {
        $this->extractor = new ExceptionExtractor();
    }

    public function testExtractFromInvokableController(): void
    {
        $result = $this->extractor->extract('/app/tests/examples/1-invokable-single-method-controller.php');
        
        $methodExceptions = $result->getMethodExceptions('__invoke');
        $this->assertTrue($methodExceptions->hasExceptions());
        $this->assertCount(1, $methodExceptions->exceptions);
        $this->assertContains(NotFoundHttpException::class, $methodExceptions->exceptions);
    }

    public function testExtractFromMultipleMethodController(): void
    {
        $result = $this->extractor->extract('/app/tests/examples/2-multiple-method-controller.php');
        
        // Test methodFour (NotFoundHttpException)
        $methodFourExceptions = $result->getMethodExceptions('methodFour');
        $this->assertTrue($methodFourExceptions->hasExceptions());
        $this->assertCount(1, $methodFourExceptions->exceptions);
        $this->assertContains(NotFoundHttpException::class, $methodFourExceptions->exceptions);

        // Test methodFive (AccessDeniedHttpException)
        $methodFiveExceptions = $result->getMethodExceptions('methodFive');
        $this->assertTrue($methodFiveExceptions->hasExceptions());
        $this->assertCount(1, $methodFiveExceptions->exceptions);
        $this->assertContains(AccessDeniedHttpException::class, $methodFiveExceptions->exceptions);

        // Test methodOne (should not contain non-HTTP exceptions)
        $methodOneExceptions = $result->getMethodExceptions('methodOne');
        $this->assertFalse($methodOneExceptions->hasExceptions());

        // Test methodTwo (no exceptions)
        $methodTwoExceptions = $result->getMethodExceptions('methodTwo');
        $this->assertFalse($methodTwoExceptions->hasExceptions());

        // Test methodThree (should not contain non-HTTP exceptions)
        $methodThreeExceptions = $result->getMethodExceptions('methodThree');
        $this->assertFalse($methodThreeExceptions->hasExceptions());
    }
}
