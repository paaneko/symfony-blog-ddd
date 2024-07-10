<?php

namespace App\Search\Blog\Domain\Entity;

use App\Search\Blog\Domain\ValueObject\ArticleIdentifier;
use App\SharedKernel\Aggregate\AggregateRoot;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table('search_blog_index')]
class Index extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: IdType::NAME, length: 255)]
    private Id $id;

    #[ORM\Column(type: ArticleIdentifierType::NAME, length: 255)]
    private ArticleIdentifier $articleIdentifier;

    #[ORM\Column(type: 'text')]
    private string $articleTitle;

    public function __construct(Id $id, ArticleIdentifier $articleIdentifier, string $articleTitle)
    {
        $this->id = $id;
        $this->articleIdentifier = $articleIdentifier;
        $this->articleTitle = $articleTitle;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getId(): Id
    {
        return $this->id;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getArticleIdentifier(): ArticleIdentifier
    {
        return $this->articleIdentifier;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getArticleTitle(): string
    {
        return $this->articleTitle;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getDomainEvents(): array
    {
        return $this->domainEvents;
    }
}