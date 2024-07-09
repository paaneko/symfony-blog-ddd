<?php

declare(strict_types=1);

namespace App\Blog\Article\Infrastructure\Repository;

use App\Blog\Article\Domain\Entity\Article;
use App\Blog\Article\Domain\Repository\ArticleRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @psalm-suppress UnusedClass
 *
 * @phpstan-ignore-next-line
 */
class PostgresArticleRepository extends ServiceEntityRepository implements ArticleRepositoryInterface
{
    /** @psalm-suppress PossiblyUnusedMethod, PossiblyUnusedParam */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function add(Article $article): void
    {
        $this->getEntityManager()->persist($article);
    }
}
