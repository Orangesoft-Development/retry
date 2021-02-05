<?php

namespace Orangesoft\Retry\Tests;

use Orangesoft\Retry\Sleeper\SleeperInterface;

class SleepAttemptsCounter implements SleeperInterface
{
    private $attemptsCount = 0;

    public function sleep(int $attempt): void
    {
        $this->attemptsCount = $attempt;
    }

    public function getAttemptsCount(): int
    {
        return $this->attemptsCount;
    }
}
