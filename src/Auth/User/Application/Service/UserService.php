<?php

declare(strict_types=1);

namespace App\Auth\User\Application\Service;

use App\Auth\User\Domain\Entity\User;
use App\Auth\User\Domain\Repository\UserRepositoryInterface;
use App\Auth\User\Domain\ValueObject\UserId;

class UserService
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function find(UserId $id): ?User
    {
        /* @phpstan-ignore-next-line */
        return $this->userRepository->find($id);
    }

    public function add(User $user): void
    {
        $this->userRepository->add($user);
    }

    public function get(UserId $userId): User
    {
        return $this->userRepository->get($userId);
    }
}
