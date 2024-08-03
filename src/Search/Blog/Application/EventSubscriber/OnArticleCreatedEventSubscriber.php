<?php

declare(strict_types=1);

namespace App\Search\Blog\Application\EventSubscriber;

use App\Blog\Article\Domain\Event\ArticleCreatedEvent;
use App\Search\Blog\Application\UseCase\AddIndex\Command;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/** @psalm-suppress UnusedClass */
final class OnArticleCreatedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ArticleCreatedEvent::class => 'addIndex',
        ];
    }

    public function addIndex(ArticleCreatedEvent $event): void
    {
        $this->messageBus->dispatch(
            new Command(
                $event->getId(),
                $event->getTitle()
            )
        );
    }
}
