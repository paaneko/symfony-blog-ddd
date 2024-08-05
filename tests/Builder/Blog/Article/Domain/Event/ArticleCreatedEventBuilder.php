<?php

declare(strict_types=1);

namespace App\Tests\Builder\Blog\Article\Domain\Event;

use App\Blog\Article\Domain\Entity\Article;
use App\Blog\Article\Domain\Event\ArticleCreatedEvent;
use Faker\Factory;

final class ArticleCreatedEventBuilder
{
    private string $id;
    private string $title;
    private string $mainImageId;

    public function __construct()
    {
        $faker = Factory::create();

        $this->id = $faker->uuid();
        $this->title = $faker->realTextBetween(25, 50);
        $this->mainImageId = $faker->uuid();
    }

    public function fromArticle(Article $article): self
    {
        $clone = clone $this;
        $clone->id = $article->getId()->getValue();
        $clone->title = $article->getTitle()->getValue();
        $clone->mainImageId = $article->getMainImageId()->getValue();

        return $clone;
    }

    public function build(): ArticleCreatedEvent
    {
        return new ArticleCreatedEvent(
            $this->id,
            $this->title,
            $this->mainImageId
        );
    }
}
