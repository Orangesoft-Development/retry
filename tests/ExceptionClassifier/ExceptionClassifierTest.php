<?php

namespace Orangesoft\Retry\Tests\ExceptionClassifier;

use PHPUnit\Framework\TestCase;
use Orangesoft\Retry\ExceptionClassifier\ExceptionClassifier;

class ExceptionClassifierTest extends TestCase
{
    public function testClassifyDefault(): void
    {
        $exceptionClassifier = new ExceptionClassifier();

        $this->assertTrue($exceptionClassifier->classify(new \Error()));
        $this->assertTrue($exceptionClassifier->classify(new \Exception()));
    }

    public function testClassifySuccess(): void
    {
        $exceptionClassifier = new ExceptionClassifier([
            \RuntimeException::class,
        ]);

        $this->assertTrue($exceptionClassifier->classify(new \RuntimeException()));
    }

    public function testClassifyFail(): void
    {
        $exceptionClassifier = new ExceptionClassifier([
            \RuntimeException::class,
        ]);

        $this->assertFalse($exceptionClassifier->classify(new \InvalidArgumentException()));
    }
}
