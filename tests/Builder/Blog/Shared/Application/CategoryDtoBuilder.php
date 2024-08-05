<?php

declare(strict_types=1);

namespace App\Tests\Builder\Blog\Shared\Application;

use App\Blog\Article\Domain\Entity\Article;
use App\Blog\Category\Domain\Entity\Category;
use App\Blog\Shared\Application\Dto\CategoryDto;
use Faker\Factory;

final class CategoryDtoBuilder
{
    private string $id;
    private string $name;
    private string $slug;

    public function __construct()
    {
        $faker = Factory::create();

        $this->id = $faker->uuid();
        $this->name = $faker->name();
        $this->slug = $faker->slug();
    }

    public function fromArticle(Article $article): self
    {
        $clone = clone $this;
        $clone->id = $article->getCategoryId()->getValue();

        return $clone;
    }

    public function fromCategory(Category $category): self
    {
        $clone = clone $this;
        $clone->id = $category->getId()->getValue();
        $clone->name = $category->getName()->getValue();
        $clone->slug = $category->getSlug()->getValue();

        return $clone;
    }

    public function build(): CategoryDto
    {
        return new CategoryDto(
            $this->id,
            $this->name,
            $this->slug
        );
    }
}
