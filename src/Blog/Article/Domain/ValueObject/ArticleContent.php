<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embeddable;
use Webmozart\Assert\Assert;

#[Embeddable]
final class ArticleContent
{
    #[ORM\Column(name: 'content', type: 'text', length: 5000)]
    private string $value;

    public function __construct(string $value)
    {
        Assert::lengthBetween($value, 250, 5000);

        $this->value = $value;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getValue(): string
    {
        return $this->value;
    }
}
