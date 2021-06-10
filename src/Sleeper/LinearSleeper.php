<?php

namespace Orangesoft\Retry\Sleeper;

final class LinearSleeper implements SleeperInterface
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
        usleep($this->milliseconds * 1000 * ($attempt + 1));
    }
}
