<?php

namespace App\Tests\Unit\Notification\Application\UseCase;

use App\Notification\Application\Dto\NotificationUserDto;
use App\Notification\Application\UseCase\ArticleCreatedNotificationCommand;
use App\Notification\Application\UseCase\ArticleCreatedNotificationCommandHandler;
use App\Notification\Domain\Event\SendArticleCreatedEmailNotificationEvent;
use App\Notification\Domain\Repository\NotificationUserRepository;
use App\Tests\UnitTestCase;
use Symfony\Component\Messenger\MessageBusInterface;

final class ArticleCreatedNotificationCommandHandlerTest extends UnitTestCase
{
    private ArticleCreatedNotificationCommand $command;

    private MessageBusInterface $eventBus;
    private NotificationUserRepository $notificationUserRepository;

    private ArticleCreatedNotificationCommandHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->command = new ArticleCreatedNotificationCommand(
            $this->faker->uuid(),
            $this->faker->uuid(),
            'Article Title'
        );

        $this->eventBus = $this->createMock(MessageBusInterface::class);
        $this->notificationUserRepository = $this->createMock(NotificationUserRepository::class);

        $this->handler = new ArticleCreatedNotificationCommandHandler(
            $this->eventBus,
            $this->notificationUserRepository
        );
    }

    public function testCanDispatchNotificationEvent(): void
    {
        $this->notificationUserRepository->expects($this->exactly(2))
            ->method('getById')
            ->willReturnOnConsecutiveCalls(
                new NotificationUserDto(
                    $this->command->followerId,
                    $this->faker->name(),
                    $this->faker->email()
                ),
                new NotificationUserDto(
                    $this->command->followeeId,
                    $this->faker->name(),
                    $this->faker->email()
                ),
            );

        $this->eventBus->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(SendArticleCreatedEmailNotificationEvent::class));

        $this->handler->__invoke($this->command);
    }
}
