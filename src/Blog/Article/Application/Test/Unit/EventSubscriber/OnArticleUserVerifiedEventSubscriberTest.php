<?php

namespace App\Blog\Article\Application\Test\Unit\EventSubscriber;

use App\Auth\User\Application\Test\Builder\ArticleUserVerifiedEventBuilder;
use App\Blog\Article\Application\EventSubscriber\OnArticleUserVerifiedEventSubscriber;
use App\Blog\Article\Application\Test\Builder\ArticleAddRequestEventBuilder;
use App\Blog\Shared\Domain\Providers\CategoryIdProvider;
use App\Blog\Shared\Domain\Providers\SectionIdProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class OnArticleUserVerifiedEventSubscriberTest extends TestCase
{
    public function testCanHandle(): void
    {
        $event = ArticleUserVerifiedEventBuilder::createFromArticleAddRequestEvent(
            (new ArticleAddRequestEventBuilder())->build()
        );

        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects($this->once())
            ->method('dispatch')->willReturn(new Envelope(new \stdClass()));

        $categoryIdProvider = $this->createStub(CategoryIdProvider::class);
        $categoryIdProvider->method('byId')->willReturn($event->getCategoryId());

        $sectionIdProvider = $this->createStub(SectionIdProvider::class);
        $sectionIdProvider->method('byId')->willReturn($event->getSectionId());

        $subscriber = new OnArticleUserVerifiedEventSubscriber(
            $messageBus,
            $categoryIdProvider,
            $sectionIdProvider
        );

        $subscriber->createArticle($event);
    }
}