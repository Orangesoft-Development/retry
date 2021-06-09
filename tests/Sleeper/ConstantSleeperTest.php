<?php

namespace Orangesoft\Retry\Tests\Sleeper;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\Timer\Timer;
use Orangesoft\Retry\Sleeper\ConstantSleeper;

class ConstantSleeperTest extends TestCase
{
    public function testSleep(): void
    {
        $sleeper = new ConstantSleeper(500);

        $timer = new Timer();

        $timer->start();

        $sleeper->sleep(0);

        $milliseconds = $timer->stop() * 1000;

        $this->assertGreaterThanOrEqual(500, $milliseconds);
    }
}
