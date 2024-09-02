<?php

namespace App\Account\Following\Application\Repository;

use App\Account\Following\Domain\ValueObject\FolloweeId;

interface FolloweeRepositoryInterface
{
    public function getById(string $id): FolloweeId;
}