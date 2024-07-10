<?php

namespace App\Blog\Article\Domain\Test\Unit;

use App\Blog\Article\Domain\Event\ArticleCreatedEvent;
use App\Blog\Article\Domain\Event\ArticleDuplicatedEvent;
use App\Blog\Article\Domain\Test\Builder\ArticleBuilder;
use App\Blog\Article\Domain\Test\Builder\CommentBuilder;
use PHPUnit\Framework\TestCase;

class ArticleEventsTest extends TestCase
{
    public function testAfterCreatingRecordArticleCreatedEvent(): void
    {
        $article = (new ArticleBuilder())->build();

        $this->assertEquals([new ArticleCreatedEvent(
            $article->getMainImageId())
        ], $article->pullDomainEvents());
    }

    public function testAfterDuplicatingItRecordsArticleDuplicatedEvent(): void
    {
        $article = (new ArticleBuilder())->buildWithoutDomainEvents();

        $article->duplicate();

        $this->assertEquals([new ArticleDuplicatedEvent()], $article->pullDomainEvents());
    }
}
