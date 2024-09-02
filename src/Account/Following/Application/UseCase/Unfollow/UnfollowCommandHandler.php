<?php

namespace App\Account\Following\Application\UseCase\Unfollow;

use App\Account\Following\Application\Repository\FolloweeRepositoryInterface;
use App\Account\Following\Application\Repository\FollowerRepositoryInterface;
use App\Account\Following\Domain\Exception\AlreadyUnfollowedException;
use App\Account\Following\Domain\Exception\FailedUnfollowActionException;
use App\Account\Following\Domain\Repository\FollowingRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class UnfollowCommandHandler
{
    public function __construct(
        private FollowingRepositoryInterface $followingRepository,
        private FolloweeRepositoryInterface $followeeRepository,
        private FollowerRepositoryInterface $followerRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(UnfollowCommand $command)
    {
        $followerId = $this->followerRepository->getById($command->followerId);
        $followeeId = $this->followeeRepository->getById($command->followeeId);

        $following = $this->followingRepository->find(['followeeId' => $followeeId, 'followerId' => $followerId]);

        if ($following === null) {
            throw new AlreadyUnfollowedException();
        }

        try {
            $this->followingRepository->remove($following);
            $this->entityManager->flush();
            $this->logger->info('Unfollow successfully', [
                'command' => $command,
            ]);
        } catch (\Exception $exception) {
            $this->logger->error('Failed Unfollow Action Exception', [
                'following' => $following,
                'exception' => $exception,
            ]);
            throw new FailedUnfollowActionException();
        }
    }
}