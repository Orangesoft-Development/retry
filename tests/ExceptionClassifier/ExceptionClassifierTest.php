<?php

namespace Orangesoft\Retry\Tests\ExceptionClassifier;

use PHPUnit\Framework\TestCase;
use Orangesoft\Retry\ExceptionClassifier\ExceptionClassifier;

class ExceptionClassifierTest extends TestCase
{
    public function testClassify(): void
    {
        $exceptionClassifier = new ExceptionClassifier([
            \RuntimeException::class,
        ]);

        $this->assertTrue($exceptionClassifier->classify(new \RuntimeException()));
        $this->assertFalse($exceptionClassifier->classify(new \InvalidArgumentException()));
    }

    public function testClassifyAllTypesByDefault(): void
    {
        $exceptionClassifier = new ExceptionClassifier();

        $this->assertTrue($exceptionClassifier->classify(new \Error()));
        $this->assertTrue($exceptionClassifier->classify(new \Exception()));
        $this->assertTrue($exceptionClassifier->classify(new \TypeError()));
        $this->assertTrue($exceptionClassifier->classify(new \RuntimeException()));
    }
}
