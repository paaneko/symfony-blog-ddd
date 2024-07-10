<?php

declare(strict_types=1);

namespace App\Search\Blog\Domain\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class IdType extends GuidType
{
    public const string NAME = 'search_blog_index_id';

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
