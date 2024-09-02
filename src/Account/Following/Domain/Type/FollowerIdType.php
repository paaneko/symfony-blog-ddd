<?php

namespace App\Account\Following\Domain\Type;

use App\Account\Following\Domain\ValueObject\FollowerId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

final class FollowerIdType extends GuidType
{
    public const string NAME = 'follower_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof FollowerId ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?FollowerId
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new FollowerId((string) $value) : null;
    }
}