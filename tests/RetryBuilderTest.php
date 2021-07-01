<?php

namespace Orangesoft\Retry\Tests;

use PHPUnit\Framework\TestCase;
use Orangesoft\Retry\Retry;
use Orangesoft\Retry\RetryBuilder;
use Orangesoft\Retry\ExceptionClassifier\ExceptionClassifier;
use Orangesoft\Retry\ExceptionClassifier\ExceptionClassifierInterface;
use Orangesoft\Retry\Sleeper\DummySleeper;
use Orangesoft\Retry\Sleeper\SleeperInterface;

class RetryBuilderTest extends TestCase
{
    public function testMaxAttempts(): void
    {
        $builder = (new RetryBuilder())->setMaxAttempts(5);

        $this->assertSame(5, $builder->getMaxAttempts());
    }

    public function testExceptionClassifier(): void
    {
        $builder = (new RetryBuilder())->setExceptionClassifier(new ExceptionClassifier());

        $this->assertInstanceOf(ExceptionClassifierInterface::class, $builder->getExceptionClassifier());
    }

    public function testSleeper(): void
    {
        $builder = (new RetryBuilder())->setSleeper(new DummySleeper());

        $this->assertInstanceOf(SleeperInterface::class, $builder->getSleeper());
    }

    public function testBuild(): void
    {
        $builder = (new RetryBuilder())
            ->setMaxAttempts(5)
            ->setExceptionClassifier(new ExceptionClassifier())
            ->setSleeper(new DummySleeper())
        ;

        $this->assertInstanceOf(Retry::class, $builder->build());
    }
}
