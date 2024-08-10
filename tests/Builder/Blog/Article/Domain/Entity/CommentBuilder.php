<?php

declare(strict_types=1);

namespace App\Tests\Builder\Blog\Article\Domain\Entity;

use App\Blog\Article\Domain\Entity\Comment;
use App\Blog\Article\Domain\ValueObject\ArticleId;
use App\Blog\Article\Domain\ValueObject\CommentEmail;
use App\Blog\Article\Domain\ValueObject\CommentId;
use App\Blog\Article\Domain\ValueObject\CommentMessage;
use App\Blog\Article\Domain\ValueObject\CommentName;
use App\Tests\Builder\UuidFactoryBuilder;
use Faker\Factory;

final class CommentBuilder
{
    private CommentId $id;

    private ArticleId $articleId;

    private CommentName $name;

    private CommentEmail $email;

    private CommentMessage $message;

    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $uuidFactory = (new UuidFactoryBuilder())->build();
        $faker = Factory::create();

        $this->id = new CommentId((string) $uuidFactory->create());
        $this->articleId = new ArticleId((string) $uuidFactory->create());
        $this->name = new CommentName($faker->name());
        $this->email = new CommentEmail($faker->email());
        $this->message = new CommentMessage($faker->realTextBetween(30, 50));
        $this->createdAt = new \DateTimeImmutable();
    }

    public function withArticleId(ArticleId $articleId): self
    {
        $clone = clone $this;
        $clone->articleId = $articleId;

        return $clone;
    }

    public function build()
    {
        return new Comment(
            $this->id,
            $this->articleId,
            $this->name,
            $this->email,
            $this->message,
            $this->createdAt
        );
    }
}
