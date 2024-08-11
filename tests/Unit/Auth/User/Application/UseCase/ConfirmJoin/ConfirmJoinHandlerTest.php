<?php

namespace App\Tests\Unit\Auth\User\Application\UseCase\ConfirmJoin;

use App\Auth\User\Application\Service\UserServiceInterface;
use App\Auth\User\Application\UseCase\ConfirmJoin\ConfirmJoinCommand;
use App\Auth\User\Application\UseCase\ConfirmJoin\ConfirmJoinHandler;
use App\Auth\User\Domain\Entity\User;
use App\Auth\User\Domain\Exception\UserAlreadyConfirmedException;
use App\Auth\User\Domain\Exception\UserNotFoundException;
use App\Tests\Builder\Auth\User\Entity\TokenBuilder;
use App\Tests\Builder\Auth\User\Entity\UserBuilder;
use App\Tests\UnitTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Clock\MockClock;

final class ConfirmJoinHandlerTest extends UnitTestCase
{
    private UserServiceInterface $userService;
    private EntityManagerInterface $entityManager;
    private ClockInterface $clock;
    private LoggerInterface $logger;
    private ConfirmJoinHandler $confirmJoinHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = $this->createMock(UserServiceInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->clock = new MockClock('2024-11-16 15:20:00');
        $this->logger = $this->createStub(LoggerInterface::class);

        $this->confirmJoinHandler = new ConfirmJoinHandler(
            $this->userService,
            $this->entityManager,
            $this->clock,
            $this->logger
        );
    }

    public function testCanConfirmJoin(): void
    {
        $token = (new TokenBuilder())
            ->withExpires($this->clock->now()->modify('+1 hour'))
            ->build();

        $user = (new UserBuilder())
            ->withToken($token)
            ->build();

        $confirmJoinCommand = new ConfirmJoinCommand(
            $user->getId()->getValue(),
            $token->getValue()
        );

        $this->userService->method('find')->willReturn($user);

        $this->entityManager->expects($this->once())->method('flush');

        $this->confirmJoinHandler->__invoke($confirmJoinCommand);

        $this->assertTrue($user->isActive());
        $this->assertNull($user->getJoinConfirmToken());
    }

    public function testCanHandleUserNotFound(): void
    {
        $confirmJoinCommand = new ConfirmJoinCommand(
            $this->faker->uuid(),
            'confirmToken',
        );

        $this->userService->method('find')->willReturn(null);

        $this->expectException(UserNotFoundException::class);

        $this->confirmJoinHandler->__invoke($confirmJoinCommand);
    }

    public function testCanHandleUserAlreadyConfirmed(): void
    {
        $confirmJoinCommand = new ConfirmJoinCommand(
            $this->faker->uuid(),
            'confirmToken',
        );
        $user = $this->createMock(User::class);

        $this->userService->method('find')->willReturn($user);
        $user->method('isActive')->willReturn(true);

        $this->expectException(UserAlreadyConfirmedException::class);

        $this->confirmJoinHandler->__invoke($confirmJoinCommand);
    }

    public function testCanHandleErrorWhilePersisting(): void
    {
        $confirmJoinCommand = new ConfirmJoinCommand(
            $this->faker->uuid(),
            'confirmToken',
        );
        $user = $this->createMock(User::class);

        $this->userService->method('find')->willReturn($user);
        $user->expects($this->once())->method('confirmJoin')
            ->with($this->equalTo($confirmJoinCommand->confirmToken), $this->clock->now());
        $this->entityManager->expects($this->once())->method('flush')
            ->willThrowException(new \Exception('Error while persisting user'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error while persisting user');

        $this->confirmJoinHandler->__invoke($confirmJoinCommand);
    }
}
