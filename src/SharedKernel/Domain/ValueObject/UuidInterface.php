<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\ValueObject;

interface UuidInterface
{
    public function getValue(): string;
}
