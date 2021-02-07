<?php

namespace Orangesoft\Retry\Sleeper;

use Orangesoft\Backoff\Sleeper\Sleeper;
use Orangesoft\Backoff\Sleeper\SleeperInterface as BackoffSleeperInterface;
use Orangesoft\Backoff\BackoffInterface;

class BackoffSleeper implements SleeperInterface
{
    /**
     * @var BackoffSleeperInterface
     */
    private $backoffSleeper;

    public function __construct(BackoffInterface $backoff)
    {
        $this->backoffSleeper = new Sleeper($backoff);
    }

    public function sleep(int $attempt): void
    {
        $this->backoffSleeper->sleep($attempt);
    }
}
