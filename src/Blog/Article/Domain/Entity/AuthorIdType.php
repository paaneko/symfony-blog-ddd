<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Entity;

use App\Blog\Article\Domain\ValueObject\AuthorId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class AuthorIdType extends GuidType
{
    public const string NAME = 'author_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof AuthorId ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?AuthorId
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new AuthorId((string) $value) : null;
    }
}
