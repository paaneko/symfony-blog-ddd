<?php

namespace App\Search\Blog\Domain\Entity;

use App\Search\Blog\Domain\ValueObject\ArticleIdentifier;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class ArticleIdentifierType extends GuidType
{
    public const string NAME = 'article_identifier_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof ArticleIdentifier ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?ArticleIdentifier
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new ArticleIdentifier((string) $value) : null;
    }
}