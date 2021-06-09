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

    public static function create(): self
    {
        $builder = new RetryBuilder();

        return new Retry($builder);
    }

    public function withMaxAttempts(int $maxAttempts): self
    {
        $retry = clone $this;

        $retry->maxAttempts = $maxAttempts;

        return $retry;
    }

    public function withExceptionClassifier(ExceptionClassifierInterface $exceptionClassifier): self
    {
        $retry = clone $this;

        $retry->exceptionClassifier = $exceptionClassifier;

        return $retry;
    }

    public function withSleeper(SleeperInterface $sleeper): self
    {
        $retry = clone $this;

        $retry->sleeper = $sleeper;

        return $retry;
    }

    /**
     * @param callable $callback
     * @param mixed[] $args
     *
     * @return mixed
     *
     * @throws \Throwable
     */
    public function call(callable $callback, array $args = [])
    {
        $attempts = $this->maxAttempts;

        retrying:

        try {
            return $callback(...$args);
        } catch (\Throwable $throwable) {
            if (0 === $attempts || !$this->exceptionClassifier->classify($throwable)) {
                throw $throwable;
            }

            $this->sleeper->sleep($this->maxAttempts - $attempts);

            $attempts--;

            goto retrying;
        }
    }
}
