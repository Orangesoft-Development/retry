<?php

namespace Orangesoft\Retry\Tests;

use PHPUnit\Framework\TestCase;
use Orangesoft\Retry\Retry;
use Orangesoft\Retry\RetryInterface;

class RetryTest extends TestCase
{
    public function testCreate(): void
    {
        $retry = Retry::create();

        $this->assertInstanceOf(Retry::class, $retry);
        $this->assertInstanceOf(RetryInterface::class, $retry);
    }

    public function testMaxTries(): void
    {
        $retry = Retry::create()->maxTries(3);

        $this->assertInstanceOf(Retry::class, $retry);
    }

    public function testForException(): void
    {
        $retry = Retry::create()->forException(
            \RuntimeException::class,
            \InvalidArgumentException::class
        );

        $this->assertInstanceOf(Retry::class, $retry);
    }

    public function testWithIntegerDelay(): void
    {
        $retry = Retry::create()->withDelay(500);

        $this->assertInstanceOf(Retry::class, $retry);
    }

    public function testWithCallableDelay(): void
    {
        $retry = Retry::create()->withDelay(function (int $attempt) {
            usleep(500 * 1000 * ($attempt + 1));
        });

        $this->assertInstanceOf(Retry::class, $retry);
    }

    public function testCallSignature(): void
    {
        $retry = Retry::create()->maxTries(1);

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
        $counter = 0;

        $this->expectException(\RuntimeException::class);

        try {
            Retry::create()
                ->maxTries(3)
                ->forException(\RuntimeException::class)
                ->call(function () use (&$counter) {
                    throw new \RuntimeException('OK', $counter++);
                })
            ;
        } catch (\RuntimeException $e) {
            $this->assertSame('OK', $e->getMessage());
            $this->assertSame(3, $e->getCode());

            throw $e;
        }
    }

    public function testAttemptsCounter(): void
    {
        $counter = new AttemptsCounter();

        try {
            Retry::create()
                ->maxTries(3)
                ->forException(\RuntimeException::class)
                ->withDelay($counter)
                ->call(function () {
                    throw new \RuntimeException();
                })
            ;
        } catch (\RuntimeException $e) {
            $this->assertSame(2, $counter->getAllAttempts());
        }
    }
}
