<?php

namespace App\Tests\Builder\Account\Following\Entity;

use App\Account\Following\Domain\Entity\Following;
use App\Account\Following\Domain\ValueObject\FolloweeId;
use App\Account\Following\Domain\ValueObject\FollowerId;
use Faker\Factory;

final class FollowingBuilder
{
    private FollowerId $followerId;

    private FolloweeId $followeeId;

    private \DateTimeImmutable $followedAt;

    public function __construct()
    {
        $factory = Factory::create();

        $this->followerId = new FollowerId($factory->uuid());
        $this->followeeId = new FolloweeId($factory->uuid());
        $this->followedAt = new \DateTimeImmutable();
    }

    public function build(): Following
    {
        return new Following(
            new FollowerId($this->followerId),
            new FolloweeId($this->followeeId),
            $this->followedAt
        );
    }
}