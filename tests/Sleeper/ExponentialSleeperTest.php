<?php

namespace Orangesoft\Retry\Tests\Sleeper;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\Timer\Timer;
use Orangesoft\Retry\Sleeper\ExponentialSleeper;

class ExponentialSleeperTest extends TestCase
{
    public function testSleep(): void
    {
        $sleeper = new ExponentialSleeper(100, 2);

        $timer = new Timer();

        $timer->start();

        $sleeper->sleep(4);

        $milliseconds = $timer->stop() * 1000;

        $this->assertGreaterThanOrEqual(1600, $milliseconds);
    }
}
