<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Entity;

use App\Blog\Article\Domain\ValueObject\MainImageId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class MainImageIdType extends GuidType
{
    public const string NAME = 'main_image_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof MainImageId ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?MainImageId
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new MainImageId((string) $value) : null;
    }
}
