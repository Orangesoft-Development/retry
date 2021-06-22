# Retry

[![Build Status](https://img.shields.io/travis/com/Orangesoft-Development/retry/main?style=plastic)](https://travis-ci.com/Orangesoft-Development/retry)
[![Latest Stable Version](https://img.shields.io/packagist/v/orangesoft/retry?style=plastic)](https://packagist.org/packages/orangesoft/retry)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/orangesoft/retry?style=plastic&color=8892BF)](https://packagist.org/packages/orangesoft/retry)
[![Total Downloads](https://img.shields.io/packagist/dt/orangesoft/retry?style=plastic)](https://packagist.org/packages/orangesoft/retry)
[![License](https://img.shields.io/packagist/l/orangesoft/retry?style=plastic&color=428F7E)](https://packagist.org/packages/orangesoft/retry)

Retry tool for exceptions.

## Installation

You can install the latest version via [Composer](https://getcomposer.org/):

```text
composer require orangesoft/retry
```

This package requires PHP 7.2 or later.

## Quick usage

By default max attempts is 3, ExceptionClassifier catch all exceptions and Sleeper is disabled:

```php
<?php

use Orangesoft\Retry\Retry;
use Orangesoft\Retry\RetryBuilder;
use Orangesoft\Retry\ExceptionClassifier\ExceptionClassifier;
use Orangesoft\Retry\Sleeper\DummySleeper;

$retry = (new RetryBuilder())
    ->setMaxAttempts(3)
    ->setExceptionClassifier(new ExceptionClassifier())
    ->setSleeper(new DummySleeper())
    ->build()
;
```

You can also get an instance in a easier way with default options:

```php
$retry = Retry::create();
```

Put the code in a callback function and call it:

```php
$result = $retry->call(function (): int {
    $random = mt_rand(1, 10);
        
    if (0 === $random % 2) {
        throw new \RuntimeException();
    }
        
    return $random;
});
```

Change options before call:

```php
$retry
    ->maxTries(1)
    ->forException(\RuntimeException::class)
    ->withDelay(500)
    ->call(function () {
        throw new \RuntimeException();
    })
;
```

Delay must be int as milliseconds or callable.
