<?php

namespace App\Account\Following\Application\UseCase\Follow;

use App\SharedKernel\Domain\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class FollowCommand implements CommandInterface
{
    #[Assert\Uuid]
    public string $followerId;

    #[Assert\Uuid]
    public string $followeeId;

    public function __construct(
        string $followerId,
        string $followeeId
    ) {
        $this->followerId = $followerId;
        $this->followeeId = $followeeId;
    }
}