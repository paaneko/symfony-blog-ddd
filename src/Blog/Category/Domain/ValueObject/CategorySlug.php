<?php

declare(strict_types=1);

namespace App\Blog\Category\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embeddable;
use Webmozart\Assert\Assert;

class CategorySlug
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::maxLength($value, 255);

        $this->value = $value;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getValue(): string
    {
        return $this->value;
    }
}
