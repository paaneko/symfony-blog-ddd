<?php

declare(strict_types=1);

namespace App\Blog\Category\Application\Service;

use App\Blog\Category\Domain\Entity\Category;
use App\Blog\Category\Domain\Repository\CategoryRepositoryInterface;

/** @psalm-suppress UnusedClass */
class CategoryService
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function add(Category $category): void
    {
        $this->categoryRepository->add($category);
    }
}
