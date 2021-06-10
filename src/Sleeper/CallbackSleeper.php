<?php

namespace Orangesoft\Retry\Sleeper;

final class CallbackSleeper implements SleeperInterface
{
    /**
     * @var callable
     */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function sleep(int $attempt): void
    {
        call_user_func($this->callback, $attempt);
    }
}
