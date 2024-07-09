<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Entity;

use App\Blog\Shared\Domain\Entity\ValueObject\SectionId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class SectionIdType extends GuidType
{
    public const string NAME = 'article_section_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof SectionId ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?SectionId
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new SectionId((string) $value) : null;
    }
}
