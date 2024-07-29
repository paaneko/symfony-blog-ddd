<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Blog\Article\Domain\Entity\Article;
use App\Blog\Article\Domain\Entity\Comment;
use App\Blog\Article\Domain\ValueObject\CommentEmail;
use App\Blog\Article\Domain\ValueObject\CommentId;
use App\Blog\Article\Domain\ValueObject\CommentMessage;
use App\Blog\Article\Domain\ValueObject\CommentName;

class CommentBuilder
{
    private CommentId $id;

    private Article $article;

    private CommentName $name;

    private CommentEmail $email;

    private CommentMessage $message;

    private \DateTimeImmutable $createdAt;

    public function __construct(
    ) {
        $this->id = CommentId::generate();
        $this->name = new CommentName('Lorem ipsum dolor sit amet');
        $this->email = new CommentEmail('example@email.com');
        $this->message = new CommentMessage('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
        $this->createdAt = new \DateTimeImmutable();
    }

    public function build()
    {
        $comment = new Comment(
            $this->id,
            $this->name,
            $this->email,
            $this->message,
            $this->createdAt
        );

        return $comment;
    }
}
