<?php

declare(strict_types=1);

namespace App\Blog\Shared\Domain\Providers;

use App\Blog\Category\Domain\Entity\Category;
use App\Blog\Category\Domain\Exception\CategoryNotFoundException;
use App\Blog\Shared\Application\Dto\CategoryDto;
use App\Blog\Shared\Application\Transformer\CategoryTransformer;
use App\Blog\Shared\Domain\Providers\Interfaces\CategoryProviderInterface;
use Doctrine\ORM\EntityManagerInterface;

final class CategoryProvider implements CategoryProviderInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CategoryTransformer $categoryTransformer,
    ) {
    }

    public function getById(string $id): CategoryDto
    {
        $category = $this->entityManager->find(Category::class, $id);

        if (null === $category) {
            throw new CategoryNotFoundException();
        }

        return $this->categoryTransformer->fromCategory($category);
    }
}
