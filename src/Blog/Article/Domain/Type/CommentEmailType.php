<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Type;

use App\Blog\Article\Domain\ValueObject\CommentEmail;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class CommentEmailType extends StringType
{
    public const string NAME = 'comment_email';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof CommentEmail ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?CommentEmail
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new CommentEmail((string) $value) : null;
    }
}
