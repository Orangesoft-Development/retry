<?php

namespace Orangesoft\Retry;

interface RetryInterface
{
    /**
     * @param callable $callback
     * @param mixed[] $args
     *
     * @return mixed
     *
     * @throws \Throwable
     */
    public function call(callable $callback, array $args = []);
}
