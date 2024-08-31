<?php

namespace App\Account\Following\Domain\Type;

use App\Account\Following\Domain\ValueObject\FolloweeId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

final class FolloweeIdType extends GuidType
{
    public const string NAME = 'followee_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof FolloweeId ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?FolloweeId
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new FolloweeId((string) $value) : null;
    }
}