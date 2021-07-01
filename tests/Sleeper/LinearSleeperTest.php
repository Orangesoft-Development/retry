<?php

namespace Orangesoft\Retry\Tests\Sleeper;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\Timer\Timer;
use Orangesoft\Retry\Sleeper\LinearSleeper;

class LinearSleeperTest extends TestCase
{
    public function testSleep(): void
    {
        $sleeper = new LinearSleeper(100);

        $timer = new Timer();

        $timer->start();

        $sleeper->sleep(4);

        $milliseconds = $timer->stop() * 1000;

        $this->assertGreaterThanOrEqual(500, $milliseconds);
    }
}
