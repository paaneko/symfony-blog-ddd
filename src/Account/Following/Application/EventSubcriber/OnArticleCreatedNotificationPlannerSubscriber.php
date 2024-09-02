<?php

namespace App\Account\Following\Application\EventSubcriber;

use App\Account\Following\Domain\Repository\FollowingRepositoryInterface;
use App\Account\Following\Domain\ValueObject\FolloweeId;
use App\Account\Following\Domain\ValueObject\FollowerId;
use App\Blog\Article\Domain\Event\ArticleCreatedEvent;
use App\SharedKernel\Application\Command\Notification\ArticleCreatedNotificationCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class OnArticleCreatedNotificationPlannerSubscriber
{
    public function __construct(
        private FollowingRepositoryInterface $followingRepository,
        private MessageBusInterface $commandBus
    ) {
    }

    public function __invoke(ArticleCreatedEvent $event)
    {
        $followee = new FolloweeId($event->getAuthorId());
        $followers = $this->followingRepository->findFolloweeFollowers(
            $followee
        );

        foreach ($followers as $followerId) {
            $this->commandBus->dispatch(
                new ArticleCreatedNotificationCommand(
                    $event->getTitle(),
                    $followee->getValue(),
                    $followerId
                )
            );
        }
    }
}