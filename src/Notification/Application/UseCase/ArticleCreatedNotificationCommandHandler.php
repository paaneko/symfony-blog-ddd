<?php

namespace App\Notification\Application\UseCase;

use App\Notification\Domain\Event\SendArticleCreatedEmailNotificationEvent;
use App\Notification\Domain\Repository\NotificationUserRepository;
use App\SharedKernel\Application\Command\Notification\ArticleCreatedNotificationCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class ArticleCreatedNotificationCommandHandler
{
    public function __construct(
        private MessageBusInterface $eventBus,
        private NotificationUserRepository $notificationUserRepository
    ) {
    }

    public function __invoke(ArticleCreatedNotificationCommand $command): void
    {
        $followee = $this->notificationUserRepository->getById($command->followeeId);
        $follower = $this->notificationUserRepository->getById($command->followerId);

        $this->eventBus->dispatch(
            new SendArticleCreatedEmailNotificationEvent(
                $follower->email,
                $followee->name,
                $follower->name,
                $command->articleTitle,
        ));
    }
}