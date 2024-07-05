<?php

declare(strict_types=1);

namespace App\Image\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;

/** @psalm-suppress UnusedClass */
class IdType extends StringType
{
    public const string NAME = 'image_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof Id) {
            return parent::convertToDatabaseValue($value->getValue(), $platform);
        }
        throw ConversionException::conversionFailedInvalidType($value, self::NAME, ['null', Id::class]);
    }

    /** @throws ConversionException */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Id
    {
        /** @var string|null $value */
        $value = parent::convertToPHPValue($value, $platform);

        if (null === $value) {
            return null;
        }

        try {
            return new Id($value);
        } catch (\InvalidArgumentException $e) {
            throw ConversionException::conversionFailed($value, self::NAME, $e);
        }
    }
}
