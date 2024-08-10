<?php

declare(strict_types=1);

namespace App\Tests\Builder\Blog\Article\Application\Dto;

use App\Blog\Article\Application\Dto\ArticleAuthorDto;
use App\Blog\Article\Domain\Entity\Article;
use Faker\Factory;

final class ArticleAuthorDtoBuilder
{
    private string $id;
    private string $name;
    private string $email;

    public function __construct()
    {
        $faker = Factory::create();

        $this->id = $faker->uuid();
        $this->name = $faker->name();
        $this->email = $faker->email();
    }

    public function fromArticle(Article $article): self
    {
        $clone = clone $this;
        $clone->id = $article->getAuthorId()->getValue();

        return $clone;
    }

    public function build(): ArticleAuthorDto
    {
        return new ArticleAuthorDto(
            $this->id,
            $this->name,
            $this->email
        );
    }
}
