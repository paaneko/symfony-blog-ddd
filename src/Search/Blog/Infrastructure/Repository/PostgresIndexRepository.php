<?php

declare(strict_types=1);

namespace App\Search\Blog\Infrastructure\Repository;

use App\Search\Blog\Domain\Entity\ArticleIndex;
use App\Search\Blog\Domain\Repository\IndexRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @psalm-suppress UnusedClass
 *
 * @phpstan-ignore-next-line
 */
class PostgresIndexRepository extends ServiceEntityRepository implements IndexRepositoryInterface
{
    /** @psalm-suppress PossiblyUnusedParam */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleIndex::class);
    }

    public function add(ArticleIndex $index): void
    {
        $this->getEntityManager()->persist($index);
    }
}
