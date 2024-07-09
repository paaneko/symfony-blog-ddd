<?php

declare(strict_types=1);

namespace App\Auth\User\Application\UseCase\Add;

use App\Auth\User\Application\Service\UserService;
use App\Auth\User\Domain\Entity\Id;
use App\Auth\User\Domain\Entity\User;
use App\Auth\User\Domain\ValueObject\Email;
use App\Auth\User\Domain\ValueObject\Name;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(private EntityManagerInterface $entityManager, private UserService $userService)
    {
    }

    public function handle(Command $command): Id
    {
        $user = new User(
            $userId = Id::generate(),
            new Name($command->name),
            new Email($command->email)
        );

        $this->userService->add($user);

        $this->entityManager->flush();

        return $userId;
    }
}
