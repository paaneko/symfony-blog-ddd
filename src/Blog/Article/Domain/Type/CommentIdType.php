<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Type;

use App\Blog\Article\Domain\ValueObject\CommentId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class CommentIdType extends GuidType
{
    public const string NAME = 'comment_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof CommentId ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?CommentId
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new CommentId((string) $value) : null;
    }
}
