<?php

declare(strict_types=1);

namespace App\Blog\Shared\Application\Transformer;

use App\Blog\Category\Domain\Entity\Category;
use App\Blog\Shared\Application\Dto\CategoryDto;

final class CategoryTransformer
{
    public function fromCategory(Category $category): CategoryDto
    {
        return new CategoryDto(
            id: $category->getId()->getValue(),
            name: $category->getName()->getValue(),
            slug: $category->getSlug()->getValue()
        );
    }
}
