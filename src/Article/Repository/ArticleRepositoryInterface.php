<?php

declare(strict_types=1);

namespace App\Article\Repository;

use App\Article\Entity\Article;

interface ArticleRepositoryInterface
{
    /** @return Article[] */
    public function all(): array;

    public function add(Article $article): void;
}
