<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Type;

use App\Blog\Article\Domain\ValueObject\ArticleMainImageId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

final class ArticleMainImageIdType extends GuidType
{
    public const string NAME = 'article_main_image_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof ArticleMainImageId ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?ArticleMainImageId
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new ArticleMainImageId((string) $value) : null;
    }
}
