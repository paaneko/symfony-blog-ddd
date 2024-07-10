<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Entity;

use App\Blog\Article\Domain\ValueObject\Email;
use App\Blog\Article\Domain\ValueObject\Message;
use App\Blog\Article\Domain\ValueObject\Name;
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

    #[ORM\Embedded(columnPrefix: false)]
    private Name $name;

    #[ORM\Embedded(columnPrefix: false)]
    private Email $email;

    #[ORM\Embedded(columnPrefix: false)]
    private Message $message;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(
        CommentId $id,
        Name $name,
        Email $email,
        Message $message,
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

    public function getName(): Name
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getMessage(): Message
    {
        return $this->message;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
