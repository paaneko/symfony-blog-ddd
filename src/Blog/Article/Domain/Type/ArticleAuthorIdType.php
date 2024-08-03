<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Type;

use App\Blog\Article\Domain\ValueObject\ArticleAuthorId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

final class ArticleAuthorIdType extends GuidType
{
    public const string NAME = 'article_author_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof ArticleAuthorId ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?ArticleAuthorId
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new ArticleAuthorId((string) $value) : null;
    }
}
