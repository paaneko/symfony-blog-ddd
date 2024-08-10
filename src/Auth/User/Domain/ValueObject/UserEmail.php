<?php

declare(strict_types=1);

namespace App\Auth\User\Domain\ValueObject;

use Webmozart\Assert\Assert;

final class UserEmail
{
    protected string $value;

    public function __construct(string $value)
    {
        Assert::email($value);
        Assert::maxLength($value, 255);

        $this->value = $value;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getValue(): string
    {
        return $this->value;
    }
}
