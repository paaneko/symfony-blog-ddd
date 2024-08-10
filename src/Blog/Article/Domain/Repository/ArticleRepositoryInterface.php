<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Repository;

use App\Blog\Article\Domain\Entity\Article;

interface ArticleRepositoryInterface
{
    public function add(Article $article): void;
}
