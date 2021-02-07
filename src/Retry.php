<?php

namespace Orangesoft\Retry;

use Orangesoft\Retry\ExceptionClassifier\ExceptionClassifierInterface;
use Orangesoft\Retry\Sleeper\SleeperInterface;

final class Retry implements RetryInterface
{
    /**
     * @var int
     */
    private $maxAttempts;
    /**
     * @var ExceptionClassifierInterface
     */
    private $exceptionClassifier;
    /**
     * @var SleeperInterface
     */
    private $sleeper;

    public function __construct(RetryBuilder $retryBuilder)
    {
        $this->maxAttempts = $retryBuilder->getMaxAttempts();
        $this->exceptionClassifier = $retryBuilder->getExceptionClassifier();
        $this->sleeper = $retryBuilder->getSleeper();
    }

    public static function createFromDefault(): RetryInterface
    {
        $retryBuilder = new RetryBuilder();

        return new Retry($retryBuilder);
    }

    public function withMaxAttempts(int $maxAttempts): RetryInterface
    {
        $retry = clone $this;

        $retry->maxAttempts = $maxAttempts;

        return $retry;
    }

    public function withExceptionClassifier(ExceptionClassifierInterface $exceptionClassifier): RetryInterface
    {
        $retry = clone $this;

        $retry->exceptionClassifier = $exceptionClassifier;

        return $retry;
    }

    public function withSleeper(SleeperInterface $sleeper): RetryInterface
    {
        $retry = clone $this;

        $retry->sleeper = $sleeper;

        return $retry;
    }

    public function call(callable $callback, array $args = [])
    {
        $attempts = $this->maxAttempts;

        retrying:

        try {
            return $callback(...$args);
        } catch (\Throwable $e) {
            if (0 === $attempts || !$this->exceptionClassifier->classify($e)) {
                throw $e;
            }

            $this->sleeper->sleep($this->maxAttempts - $attempts);

            $attempts--;

            goto retrying;
        }
    }
}
