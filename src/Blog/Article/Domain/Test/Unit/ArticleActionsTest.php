<?php

namespace App\Blog\Article\Domain\Test\Unit;

use App\Blog\Article\Domain\Test\Builder\ArticleBuilder;
use App\Blog\Article\Domain\Test\Builder\CommentBuilder;
use App\Blog\Article\Domain\ValueObject\Views;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class ArticleActionsTest extends TestCase
{
    public function testCanAddComment(): void
    {
        $article = (new ArticleBuilder())->build();

        $comment = (new CommentBuilder())->build();

        $article->addComment($comment);

        $this->assertEquals($article->getComments()->first(), $comment);
    }

    public function testCanDuplicate(): void
    {
        $article = (new ArticleBuilder())->withComment()->build();
        $article->incrementViews();

        $duplication = $article->duplicate();

        $this->assertNotSame($article->getId(), $duplication->getId());
        $this->assertEquals($article->getTitle(), $duplication->getTitle());
        $this->assertEquals($article->getContent(), $duplication->getContent());
        $this->assertEquals($duplication->getComments(), new ArrayCollection());
        $this->assertEquals($article->getContent(), $duplication->getContent());
        $this->assertEquals($article->getAuthorId(), $duplication->getAuthorId());
        $this->assertEquals($article->getMainImageId(), $duplication->getMainImageId());
        $this->assertEquals($article->getCategoryId(), $duplication->getCategoryId());
        $this->assertEquals($article->getSectionId(), $duplication->getSectionId());
        $this->assertEquals($duplication->getViews(), Views::init());
        $this->assertNotSame($article->getDateTime(), $duplication->getDateTime());
    }

    public function testCanIncrementViews(): void
    {
        $article = (new ArticleBuilder())->build();

        $article->incrementViews();

        $this->assertEquals(1, $article->getViews()->getValue());
    }
}