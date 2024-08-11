<?php

declare(strict_types=1);

namespace App\Auth\User\Domain\Type;

use App\Auth\User\Domain\ValueObject\UserStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class UserStatusType extends StringType
{
    public const string NAME = 'user_status';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof UserStatus ? $value->getStatusName() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?UserStatus
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new UserStatus((string) $value) : null;
    }
}
