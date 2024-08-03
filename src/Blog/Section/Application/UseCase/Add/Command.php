<?php

declare(strict_types=1);

namespace App\Blog\Section\Application\UseCase\Add;

final readonly class Command
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
