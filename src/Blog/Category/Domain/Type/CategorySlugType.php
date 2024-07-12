<?php

namespace App\Blog\Category\Domain\Type;

use App\Blog\Category\Domain\ValueObject\CategorySlug;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class CategorySlugType extends StringType
{
    public const string NAME = 'category_slug';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof CategorySlug ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?CategorySlug
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new CategorySlug((string) $value) : null;
    }
}