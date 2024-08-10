<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\ValueObject;

use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

final class CommentId
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::uuid($value);
        $this->value = $value;
    }

    public static function generate(): CommentId
    {
        return new self(Uuid::v4()->toString());
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
