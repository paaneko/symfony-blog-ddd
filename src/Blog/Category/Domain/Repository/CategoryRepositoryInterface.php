<?php

declare(strict_types=1);

namespace App\Blog\Category\Domain\Repository;

use App\Blog\Category\Domain\Entity\Category;

interface CategoryRepositoryInterface
{
    public function add(Category $category): void;
}
