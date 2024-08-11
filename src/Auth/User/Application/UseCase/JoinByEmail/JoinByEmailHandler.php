<?php

declare(strict_types=1);

namespace App\Auth\User\Application\UseCase\JoinByEmail;

use App\Auth\User\Application\Factory\TokenFactoryInterface;
use App\Auth\User\Application\Service\UserServiceInterface;
use App\Auth\User\Domain\Entity\User;
use App\Auth\User\Domain\Exception\EmailAlreadyRegisteredException;
use App\Auth\User\Domain\ValueObject\UserEmail;
use App\Auth\User\Domain\ValueObject\UserId;
use App\Auth\User\Domain\ValueObject\UserName;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Uid\Factory\UuidFactory;

#[AsMessageHandler]
final class JoinByEmailHandler
{
    public function __construct(
        private MessageBusInterface $eventBus,
        private UserServiceInterface $userService,
        private EntityManagerInterface $entityManager,
        private PasswordHasherFactoryInterface $passwordHasherFactory,
        private TokenFactoryInterface $tokenFactory,
        private ClockInterface $clock,
        private UuidFactory $uuidFactory,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(JoinByEmailCommand $command): void
    {
        $email = new UserEmail($command->email);
        $timeNow = $this->clock->now();

        if ($this->userService->hasByEmail($email)) {
            throw new EmailAlreadyRegisteredException();
        }

        $user = User::requestJoinByEmail(
            new UserId((string) $this->uuidFactory->create()),
            new UserName($command->name),
            $email,
            $this->passwordHasherFactory->getPasswordHasher(User::class)
                ->hash($command->password),
            $this->tokenFactory->generate($timeNow),
            $timeNow,
        );

        try {
            $this->entityManager->beginTransaction();
            $this->userService->add($user);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $exception) {
            $this->entityManager->rollback();
            $this->logger->error('Failed to persist register user', [
                'user' => $user,
                'command' => $command,
                'exception' => $exception,
            ]);
            throw $exception;
        }

        foreach ($user->pullDomainEvents() as $domainEvent) {
            $this->eventBus->dispatch($domainEvent);
        }
    }
}
