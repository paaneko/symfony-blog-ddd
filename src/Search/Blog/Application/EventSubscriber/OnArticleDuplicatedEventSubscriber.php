<?php

namespace App\Search\Blog\Application\EventSubscriber;

use App\Blog\Article\Domain\Event\ArticleDuplicatedEvent;
use App\Search\Blog\Application\UseCase\AddIndex\Command;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class OnArticleDuplicatedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ArticleDuplicatedEvent::class => 'addIndex'
        ];
    }

    public function addIndex(ArticleDuplicatedEvent $event): void
    {
        $this->messageBus->dispatch(
            new Command(
                $event->getId(),
                $event->getTitle()
            )
        );
    }
}