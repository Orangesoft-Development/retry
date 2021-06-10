<?php

namespace Orangesoft\Retry\Sleeper;

final class ConstantSleeper implements SleeperInterface
{
    /**
     * @var int
     */
    private $milliseconds;

    public function __construct(int $milliseconds)
    {
        $this->milliseconds = $milliseconds;
    }

    public function sleep(int $attempt): void
    {
        usleep($this->milliseconds * 1000);
    }
}
