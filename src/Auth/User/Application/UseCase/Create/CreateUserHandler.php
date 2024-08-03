<?php

declare(strict_types=1);

namespace App\Auth\User\Application\UseCase\Create;

use App\Auth\User\Application\Service\UserService;
use App\Auth\User\Domain\Entity\User;
use App\Auth\User\Domain\ValueObject\UserEmail;
use App\Auth\User\Domain\ValueObject\UserId;
use App\Auth\User\Domain\ValueObject\UserName;
use Doctrine\ORM\EntityManagerInterface;

final class CreateUserHandler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(private EntityManagerInterface $entityManager, private UserService $userService)
    {
    }

    public function handle(CreateUserCommand $command): UserId
    {
        $user = new User(
            $userId = UserId::generate(),
            new UserName($command->name),
            new UserEmail($command->email)
        );

        $this->userService->add($user);

        $this->entityManager->flush();

        return $userId;
    }
}
