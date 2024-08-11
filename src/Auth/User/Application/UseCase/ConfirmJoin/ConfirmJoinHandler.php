<?php

declare(strict_types=1);

namespace App\Auth\User\Application\UseCase\ConfirmJoin;

use App\Auth\User\Application\Service\UserServiceInterface;
use App\Auth\User\Domain\Exception\UserAlreadyConfirmedException;
use App\Auth\User\Domain\Exception\UserNotFoundException;
use App\Auth\User\Domain\ValueObject\UserId;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ConfirmJoinHandler
{
    public function __construct(
        private UserServiceInterface $userService,
        private EntityManagerInterface $entityManager,
        private ClockInterface $clock,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(ConfirmJoinCommand $command): void
    {
        $userId = new UserId($command->userId);

        $user = $this->userService->find($userId);

        if (null === $user) {
            throw new UserNotFoundException();
        }

        if ($user->isActive()) {
            throw new UserAlreadyConfirmedException();
        }

        $user->confirmJoin($command->confirmToken, $this->clock->now());

        try {
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->logger->error('Failed confirm user attempt', [
                'user' => $user,
                'command' => $command,
                'exception' => $exception,
            ]);
            throw $exception;
        }
    }
}
