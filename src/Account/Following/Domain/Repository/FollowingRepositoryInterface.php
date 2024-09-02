<?php

namespace App\Account\Following\Domain\Repository;

use App\Account\Following\Domain\Entity\Following;
use App\Account\Following\Domain\ValueObject\FolloweeId;
use App\Account\Following\Domain\ValueObject\FollowerId;

interface FollowingRepositoryInterface
{
    public function add(Following $following): void;

    public function hasFollowing(FolloweeId $followeeId, FollowerId $followerId): bool;

    public function remove(Following $following): void;
}