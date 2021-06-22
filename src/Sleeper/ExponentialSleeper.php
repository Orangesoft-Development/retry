<?php

namespace Orangesoft\Retry\Sleeper;

final class ExponentialSleeper implements SleeperInterface
{
    /**
     * @var int
     */
    private $milliseconds;
    /**
     * @var int
     */
    private $multiplier;

    public function __construct(int $milliseconds, int $multiplier = 2)
    {
        $this->milliseconds = $milliseconds;
        $this->multiplier = $multiplier;
    }

    public function sleep(int $attempt): void
    {
        usleep($this->milliseconds * 1000 * ($this->multiplier ** $attempt));
    }
}
