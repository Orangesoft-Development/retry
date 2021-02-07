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

By default max attempts is 5, a sleeper is disabled and a exception classifier catch all exceptions:

```php
<?php

use Orangesoft\Retry\Retry;
use Orangesoft\Retry\RetryBuilder;
use Orangesoft\Retry\RetryInterface;
use Orangesoft\Retry\ExceptionClassifier\ExceptionClassifier;
use Orangesoft\Retry\Sleeper\DummySleeper;

/** @var RetryInterface $retry */
$retry = (new RetryBuilder())
    ->setMaxAttempts(5)
    ->setExceptionClassifier(new ExceptionClassifier())
    ->setSleeper(new DummySleeper())
    ->build()
;
```

The easiest way to create the retry tool with default options is to use a `createFromDefault()` method.

```php
$retry = Retry::createFromDefault();
```

The retry tool is very similar to `call_user_func_array()` function in that its method `call()` also accepts a callback and args.

```php
/**
 * @param int $min
 * @param int $max
 * 
 * @return int
 * 
 * @throws \RuntimeException
 */
$callback = function (int $min, int $max): int {
    $random = mt_rand($min, $max);
    
    if (0 === $random % 2) {
        throw new \RuntimeException();
    }
    
    return $random;
};

$args = [5, 10];
```

Now just call the `call()` method:

```php
$retry->call($callback, $args);
```

You can immediately change the configuration of the retry tool before call the callback:

```php
$retry->withMaxAttempts(10)->call($callback, $args);
```

The same can be done for the sleeper and the exception classifier.
