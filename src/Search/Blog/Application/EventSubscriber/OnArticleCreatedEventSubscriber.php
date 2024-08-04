<?php

declare(strict_types=1);

namespace App\Search\Blog\Application\EventSubscriber;

use App\Blog\Article\Domain\Event\ArticleCreatedEvent;
use App\Search\Blog\Application\UseCase\AddIndex\AddArticleIndexCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class OnArticleCreatedEventSubscriber
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    public function __invoke(ArticleCreatedEvent $event): void
    {
        $this->commandBus->dispatch(
            new AddArticleIndexCommand(
                $event->getId(),
                $event->getTitle()
            )
        );
    }
}
