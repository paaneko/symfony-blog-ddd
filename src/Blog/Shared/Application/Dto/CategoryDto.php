<?php

namespace App\Blog\Shared\Application\Dto;

readonly class CategoryDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $slug
    ) {
    }
}