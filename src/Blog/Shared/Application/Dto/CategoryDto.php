<?php

declare(strict_types=1);

namespace App\Blog\Shared\Application\Dto;

final readonly class CategoryDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $slug
    ) {
    }
}
