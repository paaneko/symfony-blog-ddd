<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Type;

use App\Blog\Article\Domain\ValueObject\CommentMessage;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TextType;

final class CommentMessageType extends TextType
{
    public const string NAME = 'comment_message';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof CommentMessage ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?CommentMessage
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new CommentMessage((string) $value) : null;
    }
}
