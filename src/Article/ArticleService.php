<?php

declare(strict_types=1);

namespace App\Article;

use App\Article\Entity\Article;
use App\Article\Repository\PostgresArticleRepository;

class ArticleService
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(private PostgresArticleRepository $articleRepository)
    {
    }

    /** @return Article[] */
    public function all(): array
    {
        return $this->articleRepository->all();
    }

    public function add(Article $article): void
    {
        $this->articleRepository->add($article);
    }
}
