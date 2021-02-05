<?php

namespace Orangesoft\Retry\Tests\ExceptionClassifier;

use PHPUnit\Framework\TestCase;
use Orangesoft\Retry\ExceptionClassifier\ExceptionClassifier;

class ExceptionClassifierTest extends TestCase
{
    public function testClassifySuccess(): void
    {
        $exceptionClassifier = new ExceptionClassifier([
            \Exception::class,
        ]);

        $this->assertTrue($exceptionClassifier->classify(new \Exception()));
    }

    public function testClassifyFail(): void
    {
        $exceptionClassifier = new ExceptionClassifier();

        $this->assertFalse($exceptionClassifier->classify(new \Exception()));
    }
}
