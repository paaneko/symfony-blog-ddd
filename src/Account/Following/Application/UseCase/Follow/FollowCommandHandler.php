<?php

namespace App\Account\Following\Application\UseCase\Follow;

use App\Account\Following\Application\Repository\FolloweeRepositoryInterface;
use App\Account\Following\Application\Repository\FollowerRepositoryInterface;
use App\Account\Following\Domain\Entity\Following;
use App\Account\Following\Domain\Exception\AlreadyFollowedException;
use App\Account\Following\Domain\Exception\CannotFollowYourselfException;
use App\Account\Following\Domain\Exception\FailedFollowActionException;
use App\Account\Following\Domain\Repository\FollowingRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class FollowCommandHandler
{
    public function __construct(
        private FollowingRepositoryInterface $followingRepository,
        private EntityManagerInterface $entityManager,
        private FollowerRepositoryInterface $followerRepository,
        private FolloweeRepositoryInterface $followeeRepository,
        private LoggerInterface $logger,
        private ClockInterface $clock
    ) {
    }

    public function __invoke(FollowCommand $command)
    {
        $followerId = $this->followerRepository->getById($command->followerId);
        $followeeId = $this->followeeRepository->getById($command->followeeId);

        if ($followerId->getValue() === $followeeId->getValue()) {
            throw new CannotFollowYourselfException();
        }

        if ($this->followingRepository->hasFollowing($followeeId, $followerId)) {
            throw new AlreadyFollowedException();
        }

        $following = new Following(
            $followerId,
            $followeeId,
            $this->clock->now()
        );

        try {
            $this->followingRepository->add($following);
            $this->entityManager->flush();
            $this->logger->info('Following created successfully', [
                'command' => $command,
            ]);
        } catch (\Exception $exception) {
            $this->logger->error('Failed Follow Action Exception', [
                'following' => $following,
                'exception' => $exception,
            ]);
            throw new FailedFollowActionException();
        }
    }
}