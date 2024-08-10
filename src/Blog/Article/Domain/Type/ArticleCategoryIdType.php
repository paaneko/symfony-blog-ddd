<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Type;

use App\Blog\Shared\Domain\Entity\ValueObject\CategoryId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

final class ArticleCategoryIdType extends GuidType
{
    public const string NAME = 'article_category_id';

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
