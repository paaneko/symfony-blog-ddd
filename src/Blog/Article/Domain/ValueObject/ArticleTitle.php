<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embeddable;
use Webmozart\Assert\Assert;

#[Embeddable]
class ArticleTitle
{
    #[ORM\Column(name: 'title', type: 'string', length: 255)]
    private string $value;

    public function __construct(string $value)
    {
        Assert::lengthBetween($value, 15, 255);

        $this->value = $value;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getValue(): string
    {
        return $this->value;
    }
}
