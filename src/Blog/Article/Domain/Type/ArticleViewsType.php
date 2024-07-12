<?php

namespace App\Blog\Article\Domain\Type;

use App\Blog\Article\Domain\ValueObject\ArticleViews;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

class ArticleViewsType extends IntegerType
{
    public const string NAME = 'article_views';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof ArticleViews ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?ArticleViews
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new ArticleViews((string) $value) : null;
    }
}