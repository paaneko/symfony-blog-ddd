<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Entity;

use App\Blog\Article\Domain\Event\ArticleCloneCreatedEvent;
use App\Blog\Article\Domain\Event\ArticleCreatedEvent;
use App\Blog\Article\Domain\ValueObject\AuthorId;
use App\Blog\Article\Domain\ValueObject\Content;
use App\Blog\Article\Domain\ValueObject\MainImageId;
use App\Blog\Article\Domain\ValueObject\Title;
use App\Blog\Article\Domain\ValueObject\Views;
use App\Blog\Shared\Domain\Entity\ValueObject\CategoryId;
use App\Blog\Shared\Domain\Entity\ValueObject\SectionId;
use App\SharedKernel\Aggregate\AggregateRoot;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'articles')]
class Article extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: IdType::NAME, length: 255)]
    private Id $id;

    #[ORM\Embedded(columnPrefix: false)]
    private Title $title;

    #[ORM\Embedded(columnPrefix: false)]
    private Content $content;

    #[ORM\Column(type: CategoryIdType::NAME, length: 255)]
    private CategoryId $categoryId;

    #[ORM\Column(type: SectionIdType::NAME, length: 255, nullable: true)]
    private ?SectionId $sectionId;

    #[ORM\Column(type: AuthorIdType::NAME, length: 255)]
    private AuthorId $authorId;

    #[ORM\Column(type: MainImageIdType::NAME, length: 255)]
    private MainImageId $mainImageId;

    /** @var Collection<int, Comment> $comments An ArrayCollection of Comment objects */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'article', cascade: ['persist'])]
    private Collection $comments;

    #[ORM\Embedded(columnPrefix: false)]
    private Views $views;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeInterface $dateTime;

    public function __construct(
        Id $id,
        Title $title,
        Content $content,
        CategoryId $categoryId,
        ?SectionId $sectionId,
        AuthorId $authorId,
        MainImageId $mainImageId,
        Views $views,
        \DateTimeInterface $dateTime
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->categoryId = $categoryId;
        $this->sectionId = $sectionId;
        $this->authorId = $authorId;
        $this->mainImageId = $mainImageId;
        $this->views = $views;
        $this->dateTime = $dateTime;
        $this->comments = new ArrayCollection();

        $this->recordDomainEvent(new ArticleCreatedEvent(
            $mainImageId,
        ));
    }

    public function addComment(Comment $comment): void
    {
        $comment->setArticle($this);
        $this->comments[] = $comment;
    }

    public function duplicate(): self
    {
        $article = clone $this;

        $article->id = Id::generate();
        $article->title = new Title($this->title->getValue() . ' Clone');
        $article->views = Views::init();
        $article->dateTime = new \DateTimeImmutable();

        $this->recordDomainEvent(new ArticleCloneCreatedEvent());

        return $article;
    }

    /** @psalm-suppress UnusedMethod */
    public function getId(): Id
    {
        return $this->id;
    }

    /** @psalm-suppress UnusedMethod */
    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }

    /** @psalm-suppress UnusedMethod */
    public function getSectionId(): ?SectionId
    {
        return $this->sectionId;
    }

    /** @psalm-suppress UnusedMethod */
    public function getTitle(): Title
    {
        return $this->title;
    }

    /** @psalm-suppress UnusedMethod */
    public function getContent(): Content
    {
        return $this->content;
    }

    /** @psalm-suppress UnusedMethod */
    public function getAuthorId(): AuthorId
    {
        return $this->authorId;
    }

    /** @psalm-suppress UnusedMethod */
    public function getMainImageId(): MainImageId
    {
        return $this->mainImageId;
    }

    /** @return Collection<int, Comment> */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /** @psalm-suppress UnusedMethod */
    public function getDateTime(): \DateTimeInterface
    {
        return $this->dateTime;
    }

    /** @psalm-suppress UnusedMethod */
    public function getViews(): Views
    {
        return $this->views;
    }
}
