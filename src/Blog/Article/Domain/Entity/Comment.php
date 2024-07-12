<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Entity;

use App\Blog\Article\Domain\Type\CommentEmailType;
use App\Blog\Article\Domain\Type\CommentIdType;
use App\Blog\Article\Domain\Type\CommentMessageType;
use App\Blog\Article\Domain\Type\CommentNameType;
use App\Blog\Article\Domain\ValueObject\CommentId;
use App\Blog\Article\Domain\ValueObject\CommentEmail;
use App\Blog\Article\Domain\ValueObject\CommentMessage;
use App\Blog\Article\Domain\ValueObject\CommentName;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table('comments')]
class Comment
{
    #[ORM\Id]
    #[ORM\Column(type: CommentIdType::NAME, length: 255)]
    private CommentId $id;

    /** @psalm-suppress UnusedProperty */
    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    /** @phpstan-ignore-next-line */
    private Article $article;

    #[ORM\Column(type: CommentNameType::NAME, length: 255)]
    private CommentName $name;

    #[ORM\Column(type: CommentEmailType::NAME, length: 255)]
    private CommentEmail $email;

    #[ORM\Column(type: CommentMessageType::NAME, length: 255)]
    private CommentMessage $message;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(
        CommentId $id,
        CommentName $name,
        CommentEmail $email,
        CommentMessage $message,
        \DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
        $this->createdAt = $createdAt;
    }

    public function setArticle(Article $article): void
    {
        $this->article = $article;
    }

    public function getId(): CommentId
    {
        return $this->id;
    }

    public function getName(): CommentName
    {
        return $this->name;
    }

    public function getEmail(): CommentEmail
    {
        return $this->email;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getMessage(): CommentMessage
    {
        return $this->message;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
