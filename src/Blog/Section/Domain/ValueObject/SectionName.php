<?php

declare(strict_types=1);

namespace App\Blog\Section\Domain\ValueObject;

use Webmozart\Assert\Assert;

final class SectionName
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::lengthBetween($value, 2, 255);

        $this->value = $value;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getValue(): string
    {
        return $this->value;
    }
}
