<?php

namespace App\Blog\Article\Domain\Type;

use App\Blog\Article\Domain\ValueObject\ArticleContent;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TextType;

class ArticleContentType extends TextType
{
    public const string NAME = 'article_content_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof ArticleContent ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?ArticleContent
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new ArticleContent((string) $value) : null;
    }
}