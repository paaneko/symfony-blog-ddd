<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embeddable;
use Webmozart\Assert\Assert;

#[Embeddable]
final class CommentEmail
{
    #[ORM\Column(name: 'email', type: 'string', length: 255)]
    protected string $value;

    public function __construct(string $value)
    {
        Assert::email($value);
        Assert::maxLength($value, 255);

        $this->value = $value;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getValue(): string
    {
        return $this->value;
    }
}
