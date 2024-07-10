<?php

declare(strict_types=1);

namespace App\Image\Application\Test\Unit\EventSubscriber;

use App\Blog\Article\Domain\Test\Builder\ArticleCreatedEventBuilder;
use App\Image\Application\EventSubscriber\OnArticleCreatedEventSubscriber;
use App\Image\Application\Service\ImageService;
use App\Image\Domain\Entity\Image;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class OnArticleCreatedEventSubscriberTest extends TestCase
{
    public function testCanHandle()
    {
        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects($this->once())
            ->method('dispatch')->willReturn(new Envelope(new \stdClass()));

        $imageService = $this->createMock(ImageService::class);
        $imageService->expects($this->once())
            ->method('find')
            ->willReturn($this->createMock(Image::class));

        $subscriber = new OnArticleCreatedEventSubscriber(
            $messageBus,
            $imageService
        );

        $subscriber->setImageUsed((new ArticleCreatedEventBuilder())->build());
    }

    public function testItFailsIfProvidedImageDoNotExists()
    {
        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects($this->never())->method('dispatch');

        $imageService = $this->createMock(ImageService::class);
        $imageService->expects($this->once())->method('find')->willReturn(null);

        $subscriber = new OnArticleCreatedEventSubscriber(
            $messageBus,
            $imageService
        );

        $this->expectException(\DomainException::class);
        $subscriber->setImageUsed((new ArticleCreatedEventBuilder())->build());
    }
}
