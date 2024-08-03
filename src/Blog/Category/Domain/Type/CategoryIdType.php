<?php

declare(strict_types=1);

namespace App\Blog\Category\Domain\Type;

use App\Blog\Category\Domain\ValueObject\CategoryId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

/** @psalm-suppress UnusedClass */
final class CategoryIdType extends GuidType
{
    public const string NAME = 'category_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof CategoryId ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?CategoryId
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new CategoryId((string) $value) : null;
    }
}
