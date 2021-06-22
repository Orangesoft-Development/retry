<?php

namespace Orangesoft\Retry;

use Orangesoft\Retry\ExceptionClassifier\ExceptionClassifier;
use Orangesoft\Retry\ExceptionClassifier\ExceptionClassifierInterface;
use Orangesoft\Retry\Sleeper\CallbackSleeper;
use Orangesoft\Retry\Sleeper\ConstantSleeper;
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

    public function maxTries(int $maxTries): self
    {
        $retry = clone $this;

        $retry->maxAttempts = $maxTries;

        return $retry;
    }

    public function forException(string ...$exceptionClasses): self
    {
        $retry = clone $this;

        $retry->exceptionClassifier = new ExceptionClassifier($exceptionClasses);

        return $retry;
    }

    /**
     * @param int|callable|\Closure $delay
     *
     * @return self
     */
    public function withDelay($delay): self
    {
        $retry = clone $this;

        if (is_int($delay)) {
            $retry->sleeper = new ConstantSleeper($delay);
        } elseif (is_callable($delay) || $delay instanceof \Closure) {
            $retry->sleeper = new CallbackSleeper($delay);
        } else {
            throw new \InvalidArgumentException(
                sprintf('Delay must be int or callable, "%s" given.', get_debug_type($delay))
            );
        }

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
