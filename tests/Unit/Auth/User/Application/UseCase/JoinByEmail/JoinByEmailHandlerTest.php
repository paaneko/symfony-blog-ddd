<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\User\Application\UseCase\JoinByEmail;

use App\Auth\User\Application\Factory\TokenFactoryInterface;
use App\Auth\User\Application\Service\UserServiceInterface;
use App\Auth\User\Application\UseCase\JoinByEmail\JoinByEmailCommand;
use App\Auth\User\Application\UseCase\JoinByEmail\JoinByEmailHandler;
use App\Auth\User\Domain\Entity\Token;
use App\Auth\User\Domain\Entity\User;
use App\Auth\User\Domain\Event\RequestJoinByEmailEvent;
use App\Auth\User\Domain\Exception\EmailAlreadyRegisteredException;
use App\Auth\User\Domain\ValueObject\UserEmail;
use App\Tests\Builder\Auth\User\Entity\TokenBuilder;
use App\Tests\Builder\Auth\User\Entity\UserBuilder;
use App\Tests\UnitTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Uid\Factory\UuidFactory;
use Symfony\Component\Uid\Uuid;

final class JoinByEmailHandlerTest extends UnitTestCase
{
    private UserServiceInterface $userService;
    private EntityManagerInterface $entityManager;
    private PasswordHasherFactoryInterface $passwordHasherFactory;
    private TokenFactoryInterface $tokenFactory;
    private ClockInterface $clock;
    private MessageBusInterface $eventBus;
    private PasswordHasherInterface $passwordHasher;
    private UuidFactory $uuidFactory;
    private LoggerInterface $logger;

    private JoinByEmailCommand $joinByEmailCommand;
    private JoinByEmailHandler $joinByEmailHandler;
    private User $user;
    private Token $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = $this->createMock(UserServiceInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->passwordHasherFactory = $this->createMock(PasswordHasherFactoryInterface::class);
        $this->passwordHasher = $this->createStub(PasswordHasherInterface::class);
        $this->tokenFactory = $this->createStub(TokenFactoryInterface::class);
        $this->clock = new MockClock('2024-11-16 15:20:00');
        $this->eventBus = $this->createMock(MessageBusInterface::class);
        $this->uuidFactory = $this->createStub(UuidFactory::class);
        $this->logger = $this->createStub(LoggerInterface::class);

        $this->token = (new TokenBuilder())->build();
        $this->user = (new UserBuilder())
            ->withPasswordHash('hashedPassword')
            ->withCreatedAt($this->clock->now())
            ->withToken($this->token)
            ->build();

        $this->joinByEmailCommand = new JoinByEmailCommand(
            $this->user->getName()->getValue(),
            $this->user->getEmail()->getValue(),
            'password'
        );

        $this->joinByEmailHandler = new JoinByEmailHandler(
            $this->eventBus,
            $this->userService,
            $this->entityManager,
            $this->passwordHasherFactory,
            $this->tokenFactory,
            $this->clock,
            $this->uuidFactory,
            $this->logger
        );
    }

    public function testCanJoinByEmail(): void
    {
        $this->uuidFactory->method('create')->willReturn(new Uuid($this->user->getId()->getValue()));
        $this->tokenFactory->method('generate')->willReturn($this->token);
        $this->passwordHasherFactory->method('getPasswordHasher')
            ->willReturn($this->passwordHasher);
        $this->passwordHasher->method('hash')
            ->with($this->joinByEmailCommand->password)
            ->willReturn('hashedPassword');

        $this->userService->method('hasByEmail')->willReturn(false);

        $this->entityManager->expects($this->once())->method('beginTransaction');
        $this->userService->expects($this->once())->method('add')
            ->with($this->user);
        $this->entityManager->expects($this->once())->method('flush');
        $this->entityManager->expects($this->once())->method('commit');
        $this->entityManager->expects($this->never())->method('rollback');

        $this->eventBus->expects($this->once())->method('dispatch')
            ->with($this->isInstanceOf(RequestJoinByEmailEvent::class));

        $this->joinByEmailHandler->__invoke($this->joinByEmailCommand);
    }

    public function testCanHandleEmailAlreadyRegisteredException(): void
    {
        $this->userService->method('hasByEmail')
            ->with(new UserEmail($this->joinByEmailCommand->email))
            ->willReturn(true);

        $this->expectException(EmailAlreadyRegisteredException::class);

        $this->joinByEmailHandler->__invoke($this->joinByEmailCommand);
    }

    public function testShouldRollbackTransaction(): void
    {
        $this->uuidFactory->method('create')->willReturn(new Uuid($this->user->getId()->getValue()));
        $this->tokenFactory->method('generate')->willReturn($this->token);
        $this->passwordHasherFactory->method('getPasswordHasher')
            ->willReturn($this->passwordHasher);
        $this->passwordHasher->method('hash')
            ->with($this->joinByEmailCommand->password)
            ->willReturn('hashedPassword');

        $this->userService->method('hasByEmail')->willReturn(false);

        $this->entityManager->expects($this->once())->method('beginTransaction');
        $this->userService->expects($this->once())->method('add')
            ->with($this->user);
        $this->entityManager->expects($this->once())->method('flush')
            ->willThrowException(new \Exception('Exception while persisting user'));
        $this->entityManager->expects($this->never())->method('commit');
        $this->entityManager->expects($this->once())->method('rollback');

        $this->eventBus->expects($this->never())->method('dispatch');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Exception while persisting user');

        $this->joinByEmailHandler->__invoke($this->joinByEmailCommand);
    }
}
