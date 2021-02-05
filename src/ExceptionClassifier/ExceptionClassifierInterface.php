<?php

namespace Orangesoft\Retry\ExceptionClassifier;

interface ExceptionClassifierInterface
{
    public function classify(\Throwable $e): bool;
}
