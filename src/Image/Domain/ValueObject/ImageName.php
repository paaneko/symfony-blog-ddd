<?php

declare(strict_types=1);

namespace App\Image\Domain\ValueObject;

use Webmozart\Assert\Assert;

class ImageName
{
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
