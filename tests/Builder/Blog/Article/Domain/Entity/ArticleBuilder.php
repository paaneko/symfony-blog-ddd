<?php

declare(strict_types=1);

namespace App\Tests\Builder\Blog\Article\Domain\Entity;

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
use App\Tests\Builder\UuidFactoryBuilder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Faker\Factory;

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
        $uuidFactory = (new UuidFactoryBuilder())->build();
        $faker = Factory::create();

        $this->id = new ArticleId((string) $uuidFactory->create());
        $this->title = new ArticleTitle("Lorem Ipsum is simply dummy text of the printing and typesetting industry.");
        $this->content = new ArticleContent("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.");
        $this->categoryId = new CategoryId((string) $uuidFactory->create());
        $this->sectionId = new NullableSectionId((string) $uuidFactory->create());
        $this->authorId = new ArticleAuthorId((string) $uuidFactory->create());
        $this->mainImageId = new ArticleMainImageId((string) $uuidFactory->create());
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

    public function withId(ArticleId $id): self
    {
        $clone = clone $this;
        $clone->id = $id;
        return $clone;
    }

    public function withAuthor(ArticleAuthorId $authorId): self
    {
        $clone = clone $this;
        $clone->authorId = $authorId;
        return $clone;
    }

    public function withCategory(CategoryId $categoryId): self
    {
        $clone = clone $this;
        $clone->categoryId = $categoryId;
        return $clone;
    }

    public function withComment(): self
    {
        $clone = clone $this;
        $clone->comments[] = (new CommentBuilder())
            ->withArticleId($this->id)
            ->build();
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
