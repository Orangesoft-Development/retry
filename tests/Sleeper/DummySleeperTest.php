<?php

namespace Orangesoft\Retry\Tests\Sleeper;

use PHPUnit\Framework\TestCase;
use Orangesoft\Retry\Sleeper\DummySleeper;
use Orangesoft\Retry\Sleeper\SleeperInterface;

class DummySleeperTest extends TestCase
{
    public function testDummy(): void
    {
        $sleeper = new DummySleeper();

        $this->assertInstanceOf(SleeperInterface::class, $sleeper);
    }
}
