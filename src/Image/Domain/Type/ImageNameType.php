<?php

namespace App\Image\Domain\Type;

use App\Image\Domain\ValueObject\ImageName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class ImageNameType extends StringType
{
    public const string NAME = 'image_name';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof ImageName ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?ImageName
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new ImageName((string) $value) : null;
    }
}