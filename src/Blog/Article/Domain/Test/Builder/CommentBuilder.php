<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Test\Builder;

use App\Blog\Article\Domain\Entity\Article;
use App\Blog\Article\Domain\Entity\Comment;
use App\Blog\Article\Domain\Entity\CommentId;
use App\Blog\Article\Domain\ValueObject\Email;
use App\Blog\Article\Domain\ValueObject\Message;
use App\Blog\Article\Domain\ValueObject\Name;

class CommentBuilder
{
    private CommentId $id;

    private Article $article;

    private Name $name;

    private Email $email;

    private Message $message;

    private \DateTimeImmutable $createdAt;

    public function __construct(
    ) {
        $this->id = CommentId::generate();
        $this->name = new Name('Lorem ipsum dolor sit amet');
        $this->email = new Email('example@email.com');
        $this->message = new Message('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
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
