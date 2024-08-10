<?php

declare(strict_types=1);

namespace App\Auth\User\Domain\Type;

use App\Auth\User\Domain\ValueObject\UserEmail;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class UserEmailType extends StringType
{
    public const string NAME = 'user_email';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof UserEmail ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?UserEmail
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new UserEmail((string) $value) : null;
    }
}
