<?php

namespace App\Auth\User\Domain\Type;

use App\Auth\User\Domain\ValueObject\UserName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class UserNameType extends StringType
{
    public const string NAME = 'user_name';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof UserName ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?UserName
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new UserName((string) $value) : null;
    }
}