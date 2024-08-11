<?php

declare(strict_types=1);

namespace App\Auth\User\Domain\ValueObject;

use Webmozart\Assert\Assert;

final class UserStatus
{
    private const string ACTIVE = 'active';
    private const string INACTIVE = 'inactive';

    private string $statusName;

    public function __construct(string $statusName)
    {
        Assert::oneOf($statusName, [self::ACTIVE, self::INACTIVE]);

        $this->statusName = $statusName;
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public static function inactive(): self
    {
        return new self(self::INACTIVE);
    }

    public function isActive(): bool
    {
        return self::ACTIVE === $this->statusName;
    }

    public function isInActive(): bool
    {
        return self::INACTIVE === $this->statusName;
    }

    public function getStatusName(): string
    {
        return $this->statusName;
    }
}
