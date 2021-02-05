<?php

namespace Orangesoft\Retry;

interface RetryInterface
{
    /**
     * @param callable $callback
     * @param mixed[] $args
     *
     * @return mixed
     */
    public function call(callable $callback, array $args = []);
}
