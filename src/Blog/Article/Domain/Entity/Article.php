<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Entity;

use App\Blog\Article\Domain\Event\ArticleCreatedEvent;
use App\Blog\Article\Domain\Type\ArticleAuthorIdType;
use App\Blog\Article\Domain\Type\ArticleCategoryIdType;
use App\Blog\Article\Domain\Type\ArticleContentType;
use App\Blog\Article\Domain\Type\ArticleIdType;
use App\Blog\Article\Domain\Type\ArticleMainImageIdType;
use App\Blog\Article\Domain\Type\ArticleSectionIdType;
use App\Blog\Article\Domain\Type\ArticleTitleType;
use App\Blog\Article\Domain\Type\ArticleViewsType;
use App\Blog\Article\Domain\ValueObject\ArticleAuthorId;
use App\Blog\Article\Domain\ValueObject\ArticleContent;
use App\Blog\Article\Domain\ValueObject\ArticleId;
use App\Blog\Article\Domain\ValueObject\ArticleMainImageId;
use App\Blog\Article\Domain\ValueObject\ArticleTitle;
use App\Blog\Article\Domain\ValueObject\ArticleViews;
use App\Blog\Shared\Domain\Entity\ValueObject\CategoryId;
use App\Blog\Shared\Domain\Entity\ValueObject\NullableSectionId;
use App\SharedKernel\Domain\Aggregate\AggregateRoot;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'articles')]
class Article extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: ArticleIdType::NAME, length: 255)]
    private ArticleId $id;

    #[ORM\Column(type: ArticleTitleType::NAME, length: 255)]
    private ArticleTitle $title;

    #[ORM\Column(type: ArticleContentType::NAME, length: 255)]
    private ArticleContent $content;

    #[ORM\Column(type: ArticleCategoryIdType::NAME, length: 255)]
    private CategoryId $categoryId;

    #[ORM\Column(type: ArticleSectionIdType::NAME, length: 255, nullable: true)]
    private NullableSectionId $sectionId;

    #[ORM\Column(type: ArticleAuthorIdType::NAME, length: 255)]
    private ArticleAuthorId $authorId;

    #[ORM\Column(type: ArticleMainImageIdType::NAME, length: 255)]
    private ArticleMainImageId $mainImageId;

    /** @var Collection<int, Comment> $comments An ArrayCollection of Comment objects */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'article', cascade: ['persist'])]
    private Collection $comments;

    #[ORM\Column(type: ArticleViewsType::NAME, length: 255)]
    private ArticleViews $views;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $dateTime;

    public function __construct(
        ArticleId $id,
        ArticleTitle $title,
        ArticleContent $content,
        CategoryId $categoryId,
        NullableSectionId $sectionId,
        ArticleAuthorId $authorId,
        ArticleMainImageId $mainImageId,
        ArticleViews $views,
        \DateTimeImmutable $dateTime
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
            $this->id->getValue(),
            $this->title->getValue(),
            $this->mainImageId->getValue(),
        ));
    }

    public function addComment(Comment $comment): void
    {
        $comment->setArticle($this);
        $this->comments[] = $comment;
    }

    /** @psalm-suppress UnusedMethod */
    public function incrementViews(): void
    {
        $this->views = $this->views->increment();
    }

    /** @psalm-suppress UnusedMethod */
    public function getId(): ArticleId
    {
        return $this->id;
    }

    /** @psalm-suppress UnusedMethod */
    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }

    /** @psalm-suppress UnusedMethod */
    public function getSectionId(): ?NullableSectionId
    {
        return $this->sectionId;
    }

    /** @psalm-suppress UnusedMethod */
    public function getTitle(): ArticleTitle
    {
        return $this->title;
    }

    /** @psalm-suppress UnusedMethod */
    public function getContent(): ArticleContent
    {
        return $this->content;
    }

    /** @psalm-suppress UnusedMethod */
    public function getAuthorId(): ArticleAuthorId
    {
        return $this->authorId;
    }

    /** @psalm-suppress UnusedMethod */
    public function getMainImageId(): ArticleMainImageId
    {
        return $this->mainImageId;
    }

    /** @return Collection<int, Comment> */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /** @psalm-suppress UnusedMethod */
    public function getDateTime(): \DateTimeImmutable
    {
        return $this->dateTime;
    }

    /** @psalm-suppress UnusedMethod */
    public function getViews(): ArticleViews
    {
        return $this->views;
    }
}
