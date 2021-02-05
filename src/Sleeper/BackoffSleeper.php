<?php

namespace Orangesoft\Retry\Sleeper;

use Orangesoft\Backoff\BackoffInterface;

class BackoffSleeper implements SleeperInterface
{
    /**
     * @var BackoffInterface
     */
    private $backoff;

    public function __construct(BackoffInterface $backoff)
    {
        $this->backoff = $backoff;
    }

    public function sleep(int $attempt): void
    {
        $backoffTime = $this->backoff->generate($attempt);

        $microseconds = (int) $backoffTime->toMicroseconds();

        usleep($microseconds);
    }
}
