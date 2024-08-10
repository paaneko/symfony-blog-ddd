<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\Repository;

use App\Blog\Article\Application\Dto\ArticleAuthorDto;

interface ArticleAuthorRepositoryInterface
{
    public function getById(string $id): ArticleAuthorDto;
}
