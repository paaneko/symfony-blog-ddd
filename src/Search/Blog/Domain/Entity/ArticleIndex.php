<?php

declare(strict_types=1);

namespace App\Search\Blog\Domain\Entity;

use App\Search\Blog\Domain\Type\ArticleIdentifierType;
use App\Search\Blog\Domain\Type\ArticleIndexIdType;
use App\Search\Blog\Domain\ValueObject\ArticleIdentifier;
use App\Search\Blog\Domain\ValueObject\ArticleIndexId;
use App\SharedKernel\Aggregate\AggregateRoot;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table('search_blog_index')]
class ArticleIndex extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: ArticleIndexIdType::NAME, length: 255)]
    private ArticleIndexId $id;

    #[ORM\Column(type: ArticleIdentifierType::NAME, length: 255)]
    private ArticleIdentifier $articleIdentifier;

    #[ORM\Column(type: 'text')]
    private string $articleTitle;

    public function __construct(ArticleIndexId $id, ArticleIdentifier $articleIdentifier, string $articleTitle)
    {
        $this->id = $id;
        $this->articleIdentifier = $articleIdentifier;
        $this->articleTitle = $articleTitle;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getId(): ArticleIndexId
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
}
