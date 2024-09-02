<?php

namespace App\Account\Following\Application\Repository;

use App\Account\Following\Domain\ValueObject\FollowerId;

interface FollowerRepositoryInterface
{
    public function getById(string $id): FollowerId;
}