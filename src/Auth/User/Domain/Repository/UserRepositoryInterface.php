<?php

declare(strict_types=1);

namespace App\Auth\User\Domain\Repository;

use App\Auth\User\Domain\Entity\User;
use App\Auth\User\Domain\ValueObject\UserId;

interface UserRepositoryInterface
{
    public function add(User $user): void;

    /** @throws \DomainException */
    public function get(UserId $userId): User;
}
