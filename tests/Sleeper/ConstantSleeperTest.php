<?php

namespace Orangesoft\Retry\Tests\Sleeper;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\Timer\Timer;
use Orangesoft\Retry\Sleeper\ConstantSleeper;

class ConstantSleeperTest extends TestCase
{
    public function testSleep(): void
    {
        $sleeper = new ConstantSleeper(100);

        $timer = new Timer();

        $timer->start();

        $sleeper->sleep(4);

        $milliseconds = $timer->stop() * 1000;

        $this->assertGreaterThanOrEqual(100, $milliseconds);
    }
}
