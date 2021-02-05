<?php

namespace Orangesoft\Retry\Tests;

use PHPUnit\Framework\TestCase;
use Orangesoft\Retry\RetryBuilder;
use Orangesoft\Retry\RetryInterface;
use Orangesoft\Retry\ExceptionClassifier\ExceptionClassifier;
use Orangesoft\Retry\ExceptionClassifier\ExceptionClassifierInterface;
use Orangesoft\Retry\Sleeper\DummySleeper;
use Orangesoft\Retry\Sleeper\SleeperInterface;

class RetryBuilderTest extends TestCase
{
    public function testMaxAttempts(): void
    {
        $retryBuilder = (new RetryBuilder())->setMaxAttempts(5);

        $this->assertSame(5, $retryBuilder->getMaxAttempts());
    }

    public function testExceptionClassifier(): void
    {
        $retryBuilder = (new RetryBuilder())->setExceptionClassifier(new ExceptionClassifier());

        $this->assertInstanceOf(ExceptionClassifierInterface::class, $retryBuilder->getExceptionClassifier());
    }

    public function testSleeper(): void
    {
        $retryBuilder = (new RetryBuilder())->setSleeper(new DummySleeper());

        $this->assertInstanceOf(SleeperInterface::class, $retryBuilder->getSleeper());
    }

    public function testBuild(): void
    {
        $retryBuilder = (new RetryBuilder())
            ->setMaxAttempts(5)
            ->setExceptionClassifier(new ExceptionClassifier())
            ->setSleeper(new DummySleeper())
        ;

        $this->assertInstanceOf(RetryInterface::class, $retryBuilder->build());
    }
}
