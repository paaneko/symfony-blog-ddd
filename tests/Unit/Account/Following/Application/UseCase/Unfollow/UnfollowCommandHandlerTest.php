<?php

namespace App\Tests\Unit\Account\Following\Application\UseCase\Unfollow;

use App\Account\Following\Application\Repository\FolloweeRepositoryInterface;
use App\Account\Following\Application\Repository\FollowerRepositoryInterface;
use App\Account\Following\Application\UseCase\Unfollow\UnfollowCommand;
use App\Account\Following\Application\UseCase\Unfollow\UnfollowCommandHandler;
use App\Account\Following\Domain\Entity\Following;
use App\Account\Following\Domain\Exception\AlreadyUnfollowedException;
use App\Account\Following\Domain\Exception\FailedUnfollowActionException;
use App\Account\Following\Domain\Repository\FollowingRepositoryInterface;
use App\Account\Following\Infrastructure\Repository\PostgresFollowingRepository;
use App\Tests\Builder\Account\Following\Entity\FollowingBuilder;
use App\Tests\UnitTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

final class UnfollowCommandHandlerTest extends UnitTestCase
{
    private UnfollowCommand $unfollowCommand;
    private Following $following;

    private FollowingRepositoryInterface $followingRepository;
    private EntityManagerInterface $entityManager;
    private FollowerRepositoryInterface $followerRepository;
    private FolloweeRepositoryInterface $followeeRepository;
    private LoggerInterface $logger;

    private UnfollowCommandHandler $unfollowCommandHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->following = (new FollowingBuilder())->build();

        $this->unfollowCommand = new UnfollowCommand(
            $this->following->getFollowerId(),
            $this->following->getFolloweeId()
        );

        $this->followingRepository = $this->createStub(PostgresFollowingRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->followerRepository = $this->createStub(FollowerRepositoryInterface::class);
        $this->followeeRepository = $this->createStub(FolloweeRepositoryInterface::class);
        $this->logger = $this->createStub(LoggerInterface::class);

        $this->unfollowCommandHandler = new UnfollowCommandHandler(
            $this->followingRepository,
            $this->followeeRepository,
            $this->followerRepository,
            $this->entityManager,
            $this->logger
        );
    }

    public function testCanUnfollow(): void
    {
        $this->followerRepository->expects($this->once())
            ->method('getById')
            ->willReturn($this->following->getFollowerId());

        $this->followeeRepository->expects($this->once())
            ->method('getById')
            ->willReturn($this->following->getFolloweeId());

        $this->followingRepository->expects($this->once())
            ->method('find')
            ->willReturn($this->following);

        $this->followingRepository->expects($this->once())
            ->method('remove')
            ->with($this->following);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->unfollowCommandHandler->__invoke($this->unfollowCommand);
    }

    public function testAlreadyUnfollowedException(): void
    {
        $this->followerRepository->expects($this->once())
            ->method('getById')
            ->willReturn($this->following->getFollowerId());

        $this->followeeRepository->expects($this->once())
            ->method('getById')
            ->willReturn($this->following->getFolloweeId());

        $this->followingRepository->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $this->expectException(AlreadyUnfollowedException::class);

        $this->unfollowCommandHandler->__invoke($this->unfollowCommand);
    }

    public function testFailedUnfollowActionException(): void
    {
        $this->followerRepository->expects($this->once())
            ->method('getById')
            ->willReturn($this->following->getFollowerId());

        $this->followeeRepository->expects($this->once())
            ->method('getById')
            ->willReturn($this->following->getFolloweeId());

        $this->followingRepository->expects($this->once())
            ->method('find')
            ->willReturn($this->following);

        $this->followingRepository->expects($this->once())
            ->method('remove')
            ->willThrowException(new \Exception());

        $this->logger->expects($this->once())
            ->method('error');

        $this->expectException(FailedUnfollowActionException::class);

        $this->unfollowCommandHandler->__invoke($this->unfollowCommand);
    }
}
