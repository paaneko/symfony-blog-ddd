<?php

declare(strict_types=1);

namespace App\Auth\User\Application\EventSubscriber;

use App\Auth\User\Application\Event\ArticleUserVerifiedEvent;
use App\Auth\User\Application\Service\UserService;
use App\Auth\User\Domain\Entity\Id;
use App\Blog\Article\Application\Event\ArticleAddRequestEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/** @psalm-suppress UnusedClass */
class OnArticleAddRequestedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserService $userService,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ArticleAddRequestEvent::class => 'validateUser',
        ];
    }

    public function validateUser(ArticleAddRequestEvent $event): void
    {
        $userId = new Id($event->getAuthorId());

        $user = $this->userService->find($userId);

        if (is_null($user)) {
            throw new \DomainException('Author not found');
        }

        $this->eventDispatcher->dispatch(new ArticleUserVerifiedEvent(
            $event->getTitle(),
            $event->getContent(),
            $event->getCategoryId(),
            $event->getSectionId(),
            $event->getAuthorId(),
            $event->getImageId()
        ));
    }
}
