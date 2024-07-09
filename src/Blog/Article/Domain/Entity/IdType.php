<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class IdType extends GuidType
{
    public const string NAME = 'article_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof Id ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Id
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new Id((string) $value) : null;
    }
}
