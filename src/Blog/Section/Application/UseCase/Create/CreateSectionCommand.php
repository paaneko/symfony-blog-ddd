<?php

declare(strict_types=1);

namespace App\Blog\Section\Application\UseCase\Create;

use App\SharedKernel\Domain\Bus\CommandInterface;

final readonly class CreateSectionCommand implements CommandInterface
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
