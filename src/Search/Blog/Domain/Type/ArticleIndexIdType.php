<?php

declare(strict_types=1);

namespace App\Search\Blog\Domain\Type;

use App\Search\Blog\Domain\ValueObject\ArticleIndexId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

final class ArticleIndexIdType extends GuidType
{
    public const string NAME = 'search_article_index_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof ArticleIndexId ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?ArticleIndexId
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new ArticleIndexId((string) $value) : null;
    }
}
