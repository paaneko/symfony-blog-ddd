<?php

namespace App\Blog\Shared\Application\Dto;

readonly class SectionDto
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }
}