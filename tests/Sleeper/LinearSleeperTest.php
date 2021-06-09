<?php

namespace Orangesoft\Retry\Tests\Sleeper;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\Timer\Timer;
use Orangesoft\Retry\Sleeper\LinearSleeper;

class LinearSleeperTest extends TestCase
{
    public function testSleep(): void
    {
        $sleeper = new LinearSleeper(500);

        $timer = new Timer();

        $timer->start();

        $sleeper->sleep(2);

        $milliseconds = $timer->stop() * 1000;

        $this->assertGreaterThanOrEqual(1500, $milliseconds);
    }
}
