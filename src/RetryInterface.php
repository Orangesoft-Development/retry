<?php

namespace Orangesoft\Retry;

use Orangesoft\Retry\ExceptionClassifier\ExceptionClassifierInterface;
use Orangesoft\Retry\Sleeper\SleeperInterface;

interface RetryInterface
{
    public function withMaxAttempts(int $maxAttempts): self;

    public function withExceptionClassifier(ExceptionClassifierInterface $exceptionClassifier): self;

    public function withSleeper(SleeperInterface $sleeper): self;

    public function call(callable $callback, array $args = []);
}
