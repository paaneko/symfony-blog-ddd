<?php

declare(strict_types=1);

namespace App\Image\Domain\ValueObject;

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
        Assert::notEmpty($value);
        Assert::maxLength($value, 255);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
