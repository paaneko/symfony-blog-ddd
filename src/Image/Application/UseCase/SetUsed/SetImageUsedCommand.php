<?php

declare(strict_types=1);

namespace App\Image\Application\UseCase\SetUsed;

use App\SharedKernel\Domain\Bus\CommandInterface;

final readonly class SetImageUsedCommand implements CommandInterface
{
    public function __construct(public string $imageId)
    {
    }
}
