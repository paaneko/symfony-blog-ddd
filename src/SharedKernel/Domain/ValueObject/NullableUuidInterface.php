<?php

namespace App\SharedKernel\Domain\ValueObject;

interface NullableUuidInterface
{
    public function getValue(): ?string;
}