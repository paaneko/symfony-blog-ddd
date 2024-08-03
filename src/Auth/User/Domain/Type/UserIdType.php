<?php

declare(strict_types=1);

namespace App\Auth\User\Domain\Type;

use App\Auth\User\Domain\ValueObject\UserId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

final class UserIdType extends GuidType
{
    public const string NAME = 'user_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof UserId ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?UserId
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new UserId((string) $value) : null;
    }
}
