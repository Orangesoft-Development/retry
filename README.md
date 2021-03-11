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

By default max attempts is 5, ExceptionClassifier catch all exceptions and Sleeper is disabled:

```php
<?php

use Orangesoft\Retry\Retry;
use Orangesoft\Retry\RetryBuilder;
use Orangesoft\Retry\ExceptionClassifier\ExceptionClassifier;
use Orangesoft\Retry\Sleeper\DummySleeper;

$retry = (new RetryBuilder())
    ->setMaxAttempts(5)
    ->setExceptionClassifier(new ExceptionClassifier())
    ->setSleeper(new DummySleeper())
    ->build()
;
```

The easiest way to get instance Retry with default options is to use a `createFromDefault()` static method:

```php
$retry = Retry::createFromDefault();
```

Retry is very similar to `call_user_func_array()` function in that its method `call()` also passes a callback and arguments.

```php
$callback = function (int $min, int $max): int {
    $random = mt_rand($min, $max);
    
    if (0 === $random % 2) {
        throw new \Exception();
    }
    
    return $random;
};

$args = [5, 10];
```

Now just call the `call()` method. It will catch all exceptions 5 times and start over if exception is thrown or returns the following result:

```php
$retry->call($callback, $args);
```

You can  change configuration of Retry before call instantaneously:

```php
$retry->withMaxAttempts(10)->call(function () {
    throw new \Exception();
});
```

The same can be done for ExceptionClassifier and Sleeper.
