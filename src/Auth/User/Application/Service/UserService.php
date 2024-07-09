<?php

declare(strict_types=1);

namespace App\Auth\User\Application\Service;

use App\Auth\User\Domain\Entity\Id;
use App\Auth\User\Domain\Entity\User;
use App\Auth\User\Domain\Repository\UserRepositoryInterface;

class UserService
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function find(Id $id): ?User
    {
        /* @phpstan-ignore-next-line */
        return $this->userRepository->find($id);
    }

    public function add(User $user): void
    {
        $this->userRepository->add($user);
    }

    public function get(Id $userId): User
    {
        return $this->userRepository->get($userId);
    }
}
