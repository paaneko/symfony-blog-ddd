<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\Dto;

final readonly class ArticleAuthorDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email
    ) {
    }
}
