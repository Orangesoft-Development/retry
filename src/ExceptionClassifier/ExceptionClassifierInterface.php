<?php

namespace Orangesoft\Retry\ExceptionClassifier;

interface ExceptionClassifierInterface
{
    public function classify(\Throwable $throwable): bool;
}
