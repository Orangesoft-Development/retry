<?php

namespace Orangesoft\Retry\Tests\Sleeper;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\Timer\Timer;
use Orangesoft\Retry\Sleeper\CallbackSleeper;

class CallbackSleeperTest extends TestCase
{
    public function testSleep(): void
    {
        $sleeper = new CallbackSleeper(function (int $attempt) {
            usleep(100 * 1000 * ($attempt + 1));
        });

        $timer = new Timer();

        $timer->start();

        $sleeper->sleep(4);

        $milliseconds = $timer->stop() * 1000;

        $this->assertGreaterThanOrEqual(500, $milliseconds);
    }
}
