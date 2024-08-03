<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Type;

use App\Blog\Shared\Domain\Entity\ValueObject\NullableSectionId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class ArticleSectionIdType extends GuidType
{
    public const string NAME = 'article_section_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof NullableSectionId ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): NullableSectionId
    {
        /* @phpstan-ignore-next-line */
        return new NullableSectionId($value);
    }
}
