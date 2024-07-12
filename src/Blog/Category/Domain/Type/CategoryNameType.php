<?php

namespace App\Blog\Category\Domain\Type;

use App\Blog\Category\Domain\ValueObject\CategoryId;
use App\Blog\Category\Domain\ValueObject\CategoryName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class CategoryNameType extends StringType
{
    public const string NAME = 'category_name';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof CategoryName ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?CategoryName
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new CategoryName((string) $value) : null;
    }
}