<?php

declare(strict_types=1);

namespace App\Blog\Shared\Domain\Providers;

use App\Blog\Category\Domain\Exception\CategoryNotFoundException;
use App\Blog\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Blog\Shared\Application\Dto\CategoryDto;
use App\Blog\Shared\Application\Transformer\CategoryTransformer;
use App\Blog\Shared\Domain\Providers\Interfaces\CategoryProviderInterface;

final class CategoryProvider implements CategoryProviderInterface
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private CategoryTransformer $categoryTransformer
    ) {
    }

    public function getById(string $id): CategoryDto
    {
        $category = $this->categoryRepository->find($id);

        if (null === $category) {
            throw new CategoryNotFoundException();
        }

        return $this->categoryTransformer->fromCategory($category);
    }
}
