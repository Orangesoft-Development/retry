<?php

namespace Orangesoft\Retry\Tests;

class AttemptsCounter
{
    private $allAttempts = 0;

    public function __invoke(int $currentAttempt)
    {
        $this->allAttempts = $currentAttempt;
    }

    public function getAllAttempts(): int
    {
        return $this->allAttempts;
    }
}
