<?php

declare(strict_types=1);

namespace App\Auth\User\Application\UseCase\Get;

use App\Auth\User\Application\Service\UserService;
use App\Auth\User\Domain\Entity\User;
use App\Auth\User\Domain\ValueObject\UserId;

class Fetcher
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(private UserService $userService)
    {
    }

    public function fetch(Query $query): User
    {
        $userId = new UserId($query->id);

        return $this->userService->get($userId);
    }
}
