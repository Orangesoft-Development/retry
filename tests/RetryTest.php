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
    public function testCreate(): void
    {
        $retry = Retry::create();

        $this->assertInstanceOf(Retry::class, $retry);
        $this->assertInstanceOf(RetryInterface::class, $retry);
    }

    public function testWithMaxAttempts(): void
    {
        $retry = Retry::create()->withMaxAttempts(5);

        $this->assertInstanceOf(Retry::class, $retry);
    }

    public function testWithExceptionClassifier(): void
    {
        $exceptionClassifier = new ExceptionClassifier();

        $retry = Retry::create()->withExceptionClassifier($exceptionClassifier);

        $this->assertInstanceOf(Retry::class, $retry);
    }

    public function testWithSleeper(): void
    {
        $sleeper = new DummySleeper();

        $retry = Retry::create()->withSleeper($sleeper);

        $this->assertInstanceOf(Retry::class, $retry);
    }

    public function testCallSignature(): void
    {
        $retry = Retry::create()->withMaxAttempts(1);

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
        $retry = Retry::create();

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
                $testMessage = 'OK';
                $testCode = $counter++;

                throw new \RuntimeException($testMessage, $testCode);
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
