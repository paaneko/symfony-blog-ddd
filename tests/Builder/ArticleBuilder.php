<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Blog\Article\Domain\Entity\Article;
use App\Blog\Article\Domain\Entity\Comment;
use App\Blog\Article\Domain\ValueObject\ArticleAuthorId;
use App\Blog\Article\Domain\ValueObject\ArticleContent;
use App\Blog\Article\Domain\ValueObject\ArticleId;
use App\Blog\Article\Domain\ValueObject\ArticleMainImageId;
use App\Blog\Article\Domain\ValueObject\ArticleTitle;
use App\Blog\Article\Domain\ValueObject\ArticleViews;
use App\Blog\Shared\Domain\Entity\ValueObject\CategoryId;
use App\Blog\Shared\Domain\Entity\ValueObject\NullableSectionId;
use App\SharedKernel\Test\FakeUuid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class ArticleBuilder
{
    private ArticleId $id;

    private ArticleTitle $title;

    private ArticleContent $content;

    private CategoryId $categoryId;

    private ?NullableSectionId $sectionId;

    private ArticleAuthorId $authorId;

    private ArticleMainImageId $mainImageId;

    /** @var Collection<int, Comment> An ArrayCollection of Comment objects */
    private Collection $comments;

    private ArticleViews $views;

    private \DateTimeInterface $dateTime;

    public function __construct()
    {
        $this->id = ArticleId::generate();
        $this->title = new ArticleTitle('Lorem ipsum dolor sit amet');
        $this->content = new ArticleContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in.');
        $this->categoryId = new CategoryId(FakeUuid::generate());
        $this->sectionId = new NullableSectionId(FakeUuid::generate());
        $this->authorId = new ArticleAuthorId(FakeUuid::generate());
        $this->mainImageId = new ArticleMainImageId(FakeUuid::generate());
        $this->views = ArticleViews::init();
        $this->dateTime = new \DateTimeImmutable();
        $this->comments = new ArrayCollection();
    }

    public function buildWithoutDomainEvents(): Article
    {
        $article = new Article(
            $this->id,
            $this->title,
            $this->content,
            $this->categoryId,
            $this->sectionId,
            $this->authorId,
            $this->mainImageId,
            $this->views,
            $this->dateTime
        );
        $article->pullDomainEvents();

        return $article;
    }

    public function withComment(): self
    {
        $clone = clone $this;
        $clone->comments[] = (new CommentBuilder())->build();

        return $clone;
    }

    public function build(): Article
    {
        $article = new Article(
            $this->id,
            $this->title,
            $this->content,
            $this->categoryId,
            $this->sectionId,
            $this->authorId,
            $this->mainImageId,
            $this->views,
            $this->dateTime
        );

        if (!$this->comments->isEmpty()) {
            $article->addComment($this->comments->first());
        }

        return $article;
    }
}
