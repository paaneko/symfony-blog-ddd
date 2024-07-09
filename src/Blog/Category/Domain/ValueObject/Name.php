<?php

declare(strict_types=1);

namespace App\Blog\Category\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embeddable;
use Webmozart\Assert\Assert;

#[Embeddable]
class Name
{
    #[ORM\Column(name: 'name', type: 'string', length: 255)]
    private string $value;

    public function __construct(string $value)
    {
        Assert::lengthBetween($value, 2, 255);

        $this->value = $value;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getValue(): string
    {
        return $this->value;
    }
}
