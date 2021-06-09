<?php

namespace Orangesoft\Retry\ExceptionClassifier;

class ExceptionClassifier implements ExceptionClassifierInterface
{
    /**
     * @var string[]
     */
    private $exceptionTypes;

    /**
     * @param string[] $exceptionTypes
     */
    public function __construct(array $exceptionTypes = [])
    {
        if (0 === count($exceptionTypes)) {
            $exceptionTypes = [
                \Error::class,
                \Exception::class,
            ];
        }

        foreach ($exceptionTypes as $exceptionType) {
            $this->add($exceptionType);
        }
    }

    private function add(string $exceptionType): void
    {
        if (!class_exists($exceptionType) || !is_a($exceptionType, \Throwable::class, true)) {
            throw new \InvalidArgumentException(
                sprintf('Exception type must be a class that exists and can be thrown, "%s" given.', get_debug_type($exceptionType))
            );
        }

        $this->exceptionTypes[] = $exceptionType;
    }

    public function classify(\Throwable $throwable): bool
    {
        foreach ($this->exceptionTypes as $exceptionType) {
            if ($throwable instanceof $exceptionType) {
                return true;
            }
        }

        return false;
    }
}
