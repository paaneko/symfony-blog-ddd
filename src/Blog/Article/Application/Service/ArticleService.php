<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\Service;

use App\Blog\Article\Domain\Entity\Article;
use App\Blog\Article\Domain\Entity\Id;
use App\Blog\Article\Domain\Repository\ArticleRepositoryInterface;

class ArticleService
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(private ArticleRepositoryInterface $articleRepository)
    {
    }

    public function find(Id $articleId): ?Article
    {
        /* @phpstan-ignore-next-line */
        return $this->articleRepository->find($articleId);
    }

    public function add(Article $article): void
    {
        $this->articleRepository->add($article);
    }
}
