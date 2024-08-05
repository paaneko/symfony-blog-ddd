<?php

declare(strict_types=1);

namespace App\Tests\Builder\Blog\Article\Application\Dto;

use App\Blog\Article\Application\Dto\ArticleMainImageDto;
use App\Blog\Article\Domain\Entity\Article;
use Faker\Factory;

final class ArticleMainImageDtoBuilder
{
    private string $id;
    private string $name;

    public function __construct()
    {
        $faker = Factory::create();

        $this->id = $faker->uuid();
        $this->name = $faker->name();
    }

    public function fromArticle(Article $article): self
    {
        $clone = clone $this;
        $clone->id = $article->getMainImageId()->getValue();

        return $clone;
    }

    public function build(): ArticleMainImageDto
    {
        return new ArticleMainImageDto(
            $this->id,
            $this->name
        );
    }
}
