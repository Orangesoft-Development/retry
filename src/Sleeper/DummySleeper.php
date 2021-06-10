<?php

namespace Orangesoft\Retry\Sleeper;

final class DummySleeper implements SleeperInterface
{
    public function sleep(int $attempt): void
    {
    }
}
