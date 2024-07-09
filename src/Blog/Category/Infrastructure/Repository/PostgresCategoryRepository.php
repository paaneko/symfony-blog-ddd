<?php

declare(strict_types=1);

namespace App\Blog\Category\Infrastructure\Repository;

use App\Blog\Category\Domain\Entity\Category;
use App\Blog\Category\Domain\Repository\CategoryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @psalm-suppress UnusedClass
 *
 * @phpstan-ignore-next-line
 */
class PostgresCategoryRepository extends ServiceEntityRepository implements CategoryRepositoryInterface
{
    /** @psalm-suppress PossiblyUnusedParam */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function add(Category $category): void
    {
        $this->getEntityManager()->persist($category);
    }
}
