<?php

namespace Orangesoft\Retry\Tests\Sleeper;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\Timer\Timer;
use Orangesoft\Retry\Sleeper\CallbackSleeper;

class CallbackSleeperTest extends TestCase
{
    public function testSleep(): void
    {
        $sleeper = new CallbackSleeper(function () {
            usleep(500 * 1000);
        });

        $timer = new Timer();

        $timer->start();

        $sleeper->sleep(2);

        $milliseconds = $timer->stop() * 1000;

        $this->assertGreaterThanOrEqual(500, $milliseconds);
    }
}
