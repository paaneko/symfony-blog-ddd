<?php

declare(strict_types=1);

namespace App\Blog\Shared\Domain\Providers;

use App\Blog\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Blog\Shared\Domain\Providers\Interfaces\CategoryIdProviderInterface;

/** @psalm-suppress UnusedClass */
class CategoryIdProvider implements CategoryIdProviderInterface
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function byId(string $categoryId): string
    {
        /**
         * @phpstan-ignore-next-line
         */
        $category = $this->categoryRepository->find($categoryId);

        if (null === $category) {
            throw new \DomainException('Category not found');
        }

        return $categoryId;
    }
}
