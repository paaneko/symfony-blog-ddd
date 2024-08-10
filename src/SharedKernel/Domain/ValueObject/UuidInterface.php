<?php

namespace App\SharedKernel\Domain\ValueObject;

interface UuidInterface
{
    public function getValue(): string;
}