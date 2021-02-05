<?php

namespace Orangesoft\Retry\Sleeper;

interface SleeperInterface
{
    public function sleep(int $attempt): void;
}
