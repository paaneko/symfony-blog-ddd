<?php

declare(strict_types=1);

namespace App\Blog\Section\Application\UseCase\Create;

final readonly class CreateSectionCommand
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
