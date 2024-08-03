<?php

namespace App\SharedKernel\Domain\ValueObject;

use Webmozart\Assert\Assert;

abstract class NullableAggregateRootId
{
    protected ?string $value;

    public function __construct(?string $uuid)
    {
        if ($uuid !== null) {
            Assert::uuid($uuid);
        }

        $this->value = $uuid;
    }

    public function __toString(): string
    {
        return $this->value ?? '';
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}