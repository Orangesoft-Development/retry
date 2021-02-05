<?php

namespace Orangesoft\Retry\Sleeper;

class DummySleeper implements SleeperInterface
{
    public function sleep(int $attempt): void
    {
    }
}
