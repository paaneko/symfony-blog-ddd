<?php

declare(strict_types=1);

namespace App\Search\Blog\Domain\Type;

use App\Search\Blog\Domain\ValueObject\ArticleIdentifier;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

final class ArticleIdentifierType extends GuidType
{
    public const string NAME = 'search_article_identifier';

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
