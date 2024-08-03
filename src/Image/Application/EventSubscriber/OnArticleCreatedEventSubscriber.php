<?php

declare(strict_types=1);

namespace App\Image\Application\EventSubscriber;

use App\Blog\Article\Domain\Event\ArticleCreatedEvent;
use App\Image\Application\Service\ImageService;
use App\Image\Application\UseCase\SetUsed\SetImageUsedCommand;
use App\Image\Domain\ValueObject\ImageId;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/** @psalm-suppress UnusedClass */
final class OnArticleCreatedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private ImageService $imageService
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ArticleCreatedEvent::class => 'setImageUsed',
        ];
    }

    public function setImageUsed(ArticleCreatedEvent $event): void
    {
        $image = $this->imageService->find(new ImageId($event->getMainImageId()));

        if (null === $image) {
            throw new \DomainException('Image not found');
        }

        $setUsedCommand = new SetImageUsedCommand(
            $event->getMainImageId()
        );

        $this->messageBus->dispatch($setUsedCommand);
    }
}
