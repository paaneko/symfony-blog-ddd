<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\ValueObject;

use Webmozart\Assert\Assert;

abstract class NullableAggregateRootId implements NullableUuidInterface
{
    protected ?string $value;

    public function __construct(?string $uuid)
    {
        if (null !== $uuid) {
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
