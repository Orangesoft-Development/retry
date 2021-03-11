<?php

namespace Orangesoft\Retry\ExceptionClassifier;

class ExceptionClassifier implements ExceptionClassifierInterface
{
    /**
     * @var string[]
     */
    private $exceptionTypes = [];

    /**
     * @param string[] $exceptionTypes
     */
    public function __construct(array $exceptionTypes = [
        \Error::class,
        \Exception::class,
    ]) {
        foreach ($exceptionTypes as $exceptionType) {
            $this->add($exceptionType);
        }
    }

    private function add(string $exceptionType): void
    {
        if (!class_exists($exceptionType) || !is_a($exceptionType, \Throwable::class, true)) {
            throw new \InvalidArgumentException(
                sprintf('Exception type %s is invalid', $exceptionType)
            );
        }

        $this->exceptionTypes[] = $exceptionType;
    }

    public function classify(\Throwable $e): bool
    {
        foreach ($this->exceptionTypes as $exceptionType) {
            if ($e instanceof $exceptionType) {
                return true;
            }
        }

        return false;
    }
}
