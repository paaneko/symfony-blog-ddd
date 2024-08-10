<?php

declare(strict_types=1);

namespace App\Image\Domain\Type;

use App\Image\Domain\ValueObject\ImageId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

/** @psalm-suppress UnusedClass */
final class ImageIdType extends GuidType
{
    public const string NAME = 'image_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof ImageId ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?ImageId
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new ImageId((string) $value) : null;
    }
}
