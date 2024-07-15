<?php

declare(strict_types=1);

namespace App\Blog\Article\Domain\Test\Unit;

use App\Blog\Article\Domain\Event\ArticleCreatedEvent;
use App\Blog\Article\Domain\Test\Builder\ArticleBuilder;
use PHPUnit\Framework\TestCase;

class ArticleEventsTest extends TestCase
{
    public function testAfterCreatingRecordArticleCreatedEvent(): void
    {
        $article = (new ArticleBuilder())->build();

        $this->assertEquals([new ArticleCreatedEvent(
            $article->getId()->getValue(),
            $article->getTitle()->getValue(),
            $article->getMainImageId()->getValue()),
        ], $article->pullDomainEvents());
    }
}
