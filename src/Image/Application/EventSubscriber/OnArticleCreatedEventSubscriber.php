<?php

declare(strict_types=1);

namespace App\Image\Application\EventSubscriber;

use App\Blog\Article\Domain\Event\ArticleCreatedEvent;
use App\Image\Application\UseCase\SetUsed\Command;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/** @psalm-suppress UnusedClass */
class OnArticleCreatedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ArticleCreatedEvent::class => 'setImageUsed',
        ];
    }

    public function setImageUsed(ArticleCreatedEvent $event): void
    {
        $setUsedCommand = new Command(
            $event->getMainImageId()->getValue()
        );

        $this->messageBus->dispatch($setUsedCommand);
    }
}
