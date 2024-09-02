<?php

namespace App\Account\Following\Domain\Entity;

use App\Account\Following\Domain\Type\FolloweeIdType;
use App\Account\Following\Domain\Type\FollowerIdType;
use App\Account\Following\Domain\ValueObject\FolloweeId;
use App\Account\Following\Domain\ValueObject\FollowerId;
use App\SharedKernel\Domain\Aggregate\AggregateRoot;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'followings')]
class Following extends AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: FollowerIdType::NAME, length: 255)]
    private FollowerId $followerId;

    #[ORM\Id]
    #[ORM\Column(type: FolloweeIdType::NAME, length: 255)]
    private FolloweeId $followeeId;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $followedAt;

    public function __construct(
        FollowerId $followerId,
        FolloweeId $followeeId,
        \DateTimeImmutable $followedAt
    ) {
        $this->followerId = $followerId;
        $this->followeeId = $followeeId;
        $this->followedAt = $followedAt;
    }

    public function getFollowerId(): FollowerId
    {
        return $this->followerId;
    }

    public function getFolloweeId(): FolloweeId
    {
        return $this->followeeId;
    }

    public function getFollowedAt(): \DateTimeImmutable
    {
        return $this->followedAt;
    }
}