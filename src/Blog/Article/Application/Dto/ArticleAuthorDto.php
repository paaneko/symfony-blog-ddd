<?php

namespace App\Blog\Article\Application\Dto;

readonly class ArticleAuthorDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email
    ) {
    }
}