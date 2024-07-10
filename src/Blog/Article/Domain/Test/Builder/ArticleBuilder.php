<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Test\Builder;

use App\Blog\Article\Domain\Entity\Article;
use App\Blog\Article\Domain\Entity\Comment;
use App\Blog\Article\Domain\Entity\Id;
use App\Blog\Article\Domain\ValueObject\AuthorId;
use App\Blog\Article\Domain\ValueObject\Content;
use App\Blog\Article\Domain\ValueObject\MainImageId;
use App\Blog\Article\Domain\ValueObject\Title;
use App\Blog\Article\Domain\ValueObject\Views;
use App\Blog\Shared\Domain\Entity\ValueObject\CategoryId;
use App\Blog\Shared\Domain\Entity\ValueObject\SectionId;
use App\SharedKernel\Test\FakeUuid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ArticleBuilder
{
    private Id $id;

    private Title $title;

    private Content $content;

    private CategoryId $categoryId;

    private ?SectionId $sectionId;

    private AuthorId $authorId;

    private MainImageId $mainImageId;

    /** @var Collection<int, Comment> An ArrayCollection of Comment objects */
    private Collection $comments;

    private Views $views;

    private \DateTimeInterface $dateTime;

    public function __construct()
    {
        $this->id = Id::generate();
        $this->title = new Title('Lorem ipsum dolor sit amet');
        $this->content = new Content('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in.');
        $this->categoryId = new CategoryId(FakeUuid::generate());
        $this->sectionId = new SectionId(FakeUuid::generate());
        $this->authorId = new AuthorId(FakeUuid::generate());
        $this->mainImageId = new MainImageId(FakeUuid::generate());
        $this->views = Views::init();
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
