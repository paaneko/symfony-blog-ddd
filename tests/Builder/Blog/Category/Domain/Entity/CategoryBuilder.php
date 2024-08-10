<?php

declare(strict_types=1);

namespace App\Tests\Builder\Blog\Category\Domain\Entity;

use App\Blog\Category\Domain\Entity\Category;
use App\Blog\Category\Domain\ValueObject\CategoryId;
use App\Blog\Category\Domain\ValueObject\CategoryName;
use App\Blog\Category\Domain\ValueObject\CategorySlug;
use Faker\Factory;

final class CategoryBuilder
{
    private CategoryId $id;
    private CategoryName $name;
    private CategorySlug $slug;

    public function __construct()
    {
        $faker = Factory::create();

        $this->id = new CategoryId($faker->uuid());
        $this->name = new CategoryName($faker->name());
        $this->slug = new CategorySlug($faker->slug());
    }

    public function build(): Category
    {
        return new Category(
            $this->id,
            $this->name,
            $this->slug
        );
    }
}
