<?php

declare(strict_types=1);

namespace App\Blog\Shared\Application\Dto;

final readonly class SectionDto
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }
}
