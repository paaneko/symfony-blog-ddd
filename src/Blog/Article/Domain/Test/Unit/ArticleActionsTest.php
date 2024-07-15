<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Test\Unit;

use App\Blog\Article\Domain\Test\Builder\ArticleBuilder;
use App\Blog\Article\Domain\Test\Builder\CommentBuilder;
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

    public function testCanIncrementViews(): void
    {
        $article = (new ArticleBuilder())->build();

        $article->incrementViews();

        $this->assertEquals(1, $article->getViews()->getValue());
    }
}
