<?php

namespace App\SharedKernel\Domain\Exception;

abstract class DomainException extends \RuntimeException
{
    abstract public function getTranslationTemplate(): string;

    public function getTranslationArgs(): array
    {
        return [];
    }
}