<?php

namespace App\Image\Application\Test\Unit\EventSubscriber;

use App\Blog\Article\Domain\Event\ArticleCreatedEvent;
use App\Image\Application\EventSubscriber\OnArticleCreatedEventSubscriber;
use App\Image\Application\Service\ImageService;
use App\Image\Domain\Entity\Image;
use DomainException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class OnArticleCreatedEventSubscriberTest extends TestCase
{
    public function testCanHandle()
    {
        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects($this->once())
            ->method('dispatch')->willReturn(new Envelope(new stdClass()));

        $imageService = $this->createMock(ImageService::class);
        $imageService->expects($this->once())
            ->method('find')
            ->willReturn($this->createMock(Image::class));

        $event = new ArticleCreatedEvent('e1234567-e89b-12d3-a456-426614174000');

        $subscriber = new OnArticleCreatedEventSubscriber(
            $messageBus,
            $imageService
        );

        $subscriber->setImageUsed($event);
    }
    
    public function testItFailsIfProvidedImageDoNotExists()
    {
        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects($this->never())->method('dispatch');

        $imageService = $this->createMock(ImageService::class);
        $imageService->expects($this->once())->method('find')->willReturn(null);

        $event = new ArticleCreatedEvent('e1234567-e89b-12d3-a456-426614174000');

        $subscriber = new OnArticleCreatedEventSubscriber(
            $messageBus,
            $imageService
        );

        $this->expectException(DomainException::class);
        $subscriber->setImageUsed($event);
    }
}