<?php

declare(strict_types=1);

namespace App\Blog\Article\Application\EventSubscriber;

use App\Auth\User\Application\Event\OnUserVerifiedEvent;
use App\Blog\Article\Application\UseCase\Create\Command;
use App\Blog\Shared\Domain\Providers\Interfaces\CategoryIdProviderInterface;
use App\Blog\Shared\Domain\Providers\Interfaces\SectionIdProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/** @psalm-suppress UnusedClass */
class OnUserVerifiedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private CategoryIdProviderInterface $categoryIdProvider,
        private SectionIdProviderInterface $sectionIdProvider,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OnUserVerifiedEvent::class => 'createArticle',
        ];
    }

    public function createArticle(OnUserVerifiedEvent $event): void
    {
        // Code Smells
        $sectionId = $event->getSectionId();

        $createArticleCommand = new Command(
            $event->getTitle(),
            $event->getContent(),
            // Code Smells
            $this->categoryIdProvider->byId($event->getCategoryId()),
            ($sectionId) ? $this->sectionIdProvider->byId($sectionId) : null,
            $event->getAuthorId(),
            $event->getImageId()
        );

        $this->messageBus->dispatch($createArticleCommand);
    }
}
