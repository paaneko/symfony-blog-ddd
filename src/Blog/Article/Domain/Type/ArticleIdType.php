<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Type;

use App\Blog\Article\Domain\ValueObject\ArticleId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class ArticleIdType extends GuidType
{
    public const string NAME = 'article_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof ArticleId ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?ArticleId
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new ArticleId((string) $value) : null;
    }
}
