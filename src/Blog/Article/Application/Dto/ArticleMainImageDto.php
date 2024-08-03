<?php

namespace App\Blog\Article\Application\Dto;

readonly class ArticleMainImageDto
{
    public function __construct(
        public string $id,
        public string $name
    ) {
    }
}