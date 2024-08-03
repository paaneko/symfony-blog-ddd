<?php

declare(strict_types=1);

namespace App\Image\Application\UseCase\SetUsed;

final readonly class Command
{
    public function __construct(public string $imageId)
    {
    }
}
