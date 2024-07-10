<?php

namespace App\Auth\User\Application\Test\Unit\EventSubscriber;

use App\Auth\User\Application\EventSubscriber\OnArticleAddRequestedEventSubscriber;
use App\Auth\User\Application\Service\UserService;
use App\Auth\User\Domain\Test\Builder\UserBuilder;
use App\Blog\Article\Application\Test\Builder\ArticleAddRequestEventBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

class OnArticleAddRequestedEventSubscriberTest extends TestCase
{
    public function testCanHandle(): void
    {
        $userService = $this->createMock(UserService::class);
        $userService->expects($this->once())->method('find')->willReturn((new UserBuilder())->build());

        $eventDispatcher = $this->createMock(EventDispatcher::class);
        $eventDispatcher->expects($this->once())->method('dispatch');

        $subscriber = new OnArticleAddRequestedEventSubscriber(
            $userService,
            $eventDispatcher
        );

        $subscriber->validateUser((new ArticleAddRequestEventBuilder())->build());
    }

    public function testIdFailsIfProvidedAuthorDoNotExists(): void
    {
        $userService = $this->createMock(UserService::class);
        $userService->expects($this->once())->method('find')->willReturn(null);

        $eventDispatcher = $this->createMock(EventDispatcher::class);
        $eventDispatcher->expects($this->never())->method('dispatch');

        $subscriber = new OnArticleAddRequestedEventSubscriber(
            $userService,
            $eventDispatcher
        );

        $this->expectException(\DomainException::class);

        $subscriber->validateUser((new ArticleAddRequestEventBuilder())->build());
    }
}