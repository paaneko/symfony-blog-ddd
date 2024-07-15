<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Type;

use App\Blog\Article\Domain\ValueObject\ArticleTitle;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class ArticleTitleType extends StringType
{
    public const string NAME = 'article_title';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof ArticleTitle ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?ArticleTitle
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new ArticleTitle((string) $value) : null;
    }
}
