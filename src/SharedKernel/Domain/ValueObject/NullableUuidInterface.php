<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\ValueObject;

interface NullableUuidInterface
{
    public function getValue(): ?string;
}
