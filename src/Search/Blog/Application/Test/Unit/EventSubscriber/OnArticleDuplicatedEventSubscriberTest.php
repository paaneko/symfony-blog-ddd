<?php

declare(strict_types=1);

namespace App\Search\Blog\Application\Test\Unit\EventSubscriber;

use App\Blog\Article\Domain\Test\Builder\ArticleDuplicatedEventBuilder;
use App\Search\Blog\Application\EventSubscriber\OnArticleDuplicatedEventSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class OnArticleDuplicatedEventSubscriberTest extends TestCase
{
    public function testCanHandle(): void
    {
        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects($this->once())
            ->method('dispatch')->willReturn(new Envelope(new \stdClass()));

        $subscriber = new OnArticleDuplicatedEventSubscriber($messageBus);

        $subscriber->addIndex((new ArticleDuplicatedEventBuilder())->build());
    }
}
