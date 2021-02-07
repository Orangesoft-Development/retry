<?php

namespace Orangesoft\Retry\Tests;

use PHPUnit\Framework\TestCase;
use Orangesoft\Retry\Retry;
use Orangesoft\Retry\RetryBuilder;
use Orangesoft\Retry\RetryInterface;
use Orangesoft\Retry\ExceptionClassifier\ExceptionClassifier;
use Orangesoft\Retry\Sleeper\DummySleeper;

class RetryTest extends TestCase
{
    public function testCreateFromDefault(): void
    {
        $retry = Retry::createFromDefault();

        $this->assertInstanceOf(RetryInterface::class, $retry);
    }

    public function testWithMaxAttempts(): void
    {
        $retry = Retry::createFromDefault()->withMaxAttempts(5);

        $this->assertInstanceOf(RetryInterface::class, $retry);
    }

    public function testWithExceptionClassifier(): void
    {
        $exceptionClassifier = new ExceptionClassifier();

        $retry = Retry::createFromDefault()->withExceptionClassifier($exceptionClassifier);

        $this->assertInstanceOf(RetryInterface::class, $retry);
    }

    public function testWithSleeper(): void
    {
        $sleeper = new DummySleeper();

        $retry = Retry::createFromDefault()->withSleeper($sleeper);

        $this->assertInstanceOf(RetryInterface::class, $retry);
    }

    public function testCallSignature(): void
    {
        $retry = Retry::createFromDefault()->withMaxAttempts(1);

        $args = [
            'value1',
            'value2',
        ];

        $callback = function (string $arg1, string $arg2) {
            $this->assertSame('value1', $arg1);
            $this->assertSame('value2', $arg2);
        };

        $retry->call($callback, $args);
    }

    public function testCallSuccess(): void
    {
        $retry = Retry::createFromDefault();

        $result = $retry->call(function () {
            return 42;
        });

        $this->assertSame(42, $result);
    }

    public function testCallFail(): void
    {
        $exceptionClassifier = new ExceptionClassifier([
            \RuntimeException::class,
        ]);

        $retry = (new RetryBuilder())
            ->setMaxAttempts(5)
            ->setExceptionClassifier($exceptionClassifier)
            ->build()
        ;

        $counter = 0;

        $this->expectException(\RuntimeException::class);

        try {
            $retry->call(function () use (&$counter) {
                throw new \RuntimeException('OK', $counter++);
            });
        } catch (\RuntimeException $e) {
            $this->assertSame('OK', $e->getMessage());
            $this->assertSame(5, $e->getCode());

            throw $e;
        }
    }

    public function testSleepAttempts(): void
    {
        $sleepAttemptsCounter = new SleepAttemptsCounter();

        $exceptionClassifier = new ExceptionClassifier([
            \RuntimeException::class,
        ]);

        $retry = (new RetryBuilder())
            ->setMaxAttempts(5)
            ->setExceptionClassifier($exceptionClassifier)
            ->setSleeper($sleepAttemptsCounter)
            ->build()
        ;

        try {
            $retry->call(function () {
                throw new \RuntimeException();
            });
        } catch (\RuntimeException $e) {
            $this->assertSame(4, $sleepAttemptsCounter->getAttemptsCount());
        }
    }
}
