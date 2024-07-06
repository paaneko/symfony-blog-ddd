<?php

declare(strict_types=1);

namespace App\Article\Repository;

use App\Article\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

/** @psalm-suppress UnusedClass */
class PostgresArticleRepository implements ArticleRepositoryInterface
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function all(): array
    {
        $repository = $this->entityManager->getRepository(Article::class);

        return $repository->findAll();
    }

    public function add(Article $article): void
    {
        $this->entityManager->persist($article);
    }
}
