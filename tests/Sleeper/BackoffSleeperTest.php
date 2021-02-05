<?php

namespace Orangesoft\Retry\Tests\Sleeper;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\Timer\Timer;
use Orangesoft\Retry\Sleeper\BackoffSleeper;
use Orangesoft\Retry\Sleeper\SleeperInterface;
use Orangesoft\Backoff\Factory\ConstantBackoff;
use Orangesoft\Backoff\Duration\Milliseconds;

class BackoffSleeperTest extends TestCase
{
    public function testSleep(): void
    {
        $backoff = new ConstantBackoff(new Milliseconds(500));

        $backoffSleeper = new BackoffSleeper($backoff);

        $timer = new Timer();

        $timer->start();

        $backoffSleeper->sleep(0);

        $milliseconds = $timer->stop() * 1000 * 1000;

        $this->assertGreaterThanOrEqual(500, $milliseconds);
        $this->assertInstanceOf(SleeperInterface::class, $backoffSleeper);
    }
}
