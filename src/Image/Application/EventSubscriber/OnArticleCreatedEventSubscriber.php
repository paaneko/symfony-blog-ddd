<?php

declare(strict_types=1);

namespace App\Image\Application\EventSubscriber;

use App\Blog\Article\Domain\Event\ArticleCreatedEvent;
use App\Image\Application\Service\ImageService;
use App\Image\Application\UseCase\SetUsed\SetImageUsedCommand;
use App\Image\Domain\ValueObject\ImageId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class OnArticleCreatedEventSubscriber
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private ImageService $imageService
    ) {
    }

    public function __invoke(ArticleCreatedEvent $event): void
    {
        $image = $this->imageService->find(new ImageId($event->getMainImageId()));

        if (null === $image) {
            throw new \DomainException('Image not found');
        }

        $setUsedCommand = new SetImageUsedCommand(
            $event->getMainImageId()
        );

        $this->commandBus->dispatch($setUsedCommand);
    }
}
