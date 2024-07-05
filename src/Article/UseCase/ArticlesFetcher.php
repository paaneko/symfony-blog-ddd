<?php

declare(strict_types=1);

namespace App\Article\UseCase;

use App\Article\ArticleService;
use App\Article\Entity\Article;

class ArticlesFetcher
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(private ArticleService $articleService)
    {
    }

    /** @return array<string, Article[]> */
    public function fetch(): array
    {
        return [
            'data' => $this->articleService->all(),
        ];
    }
}
