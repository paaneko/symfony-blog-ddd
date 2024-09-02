<?php

namespace App\Tests\Unit\Account\Following\Application\UseCase\Follow;

use App\Account\Following\Application\Repository\FolloweeRepositoryInterface;
use App\Account\Following\Application\Repository\FollowerRepositoryInterface;
use App\Account\Following\Application\UseCase\Follow\FollowCommand;
use App\Account\Following\Application\UseCase\Follow\FollowCommandHandler;
use App\Account\Following\Domain\Entity\Following;
use App\Account\Following\Domain\Exception\AlreadyFollowedException;
use App\Account\Following\Domain\Exception\CannotFollowYourselfException;
use App\Account\Following\Domain\Repository\FollowingRepositoryInterface;
use App\Account\Following\Domain\ValueObject\FolloweeId;
use App\Account\Following\Domain\ValueObject\FollowerId;
use App\Tests\Builder\Account\Following\Entity\FollowingBuilder;
use App\Tests\UnitTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\Clock\Test\ClockSensitiveTrait;

final class FollowCommandHandlerTest extends UnitTestCase
{
    use ClockSensitiveTrait;

    private FollowCommand $followCommand;
    private Following $following;

    private FollowingRepositoryInterface $followingRepository;
    private EntityManagerInterface $entityManager;
    private FollowerRepositoryInterface $followerRepository;
    private FolloweeRepositoryInterface $followeeRepository;
    private LoggerInterface $logger;
    private ClockInterface $clock;

    private FollowCommandHandler $followCommandHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->following = (new FollowingBuilder())->build();

        $this->followCommand = new FollowCommand(
            $this->following->getFollowerId(),
            $this->following->getFolloweeId()
        );

        $this->followingRepository = $this->createStub(FollowingRepositoryInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->followerRepository = $this->createStub(FollowerRepositoryInterface::class);
        $this->followeeRepository = $this->createStub(FolloweeRepositoryInterface::class);
        $this->logger = $this->createStub(LoggerInterface::class);
        $this->clock = new MockClock($this->following->getFollowedAt());

        $this->followCommandHandler = new FollowCommandHandler(
            $this->followingRepository,
            $this->entityManager,
            $this->followerRepository,
            $this->followeeRepository,
            $this->logger,
            $this->clock
        );
    }

    public function testCanFollow(): void
    {
        $this->followerRepository->expects($this->once())
            ->method('getById')
            ->willReturn($this->following->getFollowerId());

        $this->followeeRepository->expects($this->once())
            ->method('getById')
            ->willReturn($this->following->getFolloweeId());

        $this->followingRepository->expects($this->once())
            ->method('hasFollowing')
            ->willReturn(false);

        $this->followingRepository->expects($this->once())
            ->method('add')
            ->with($this->following);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->followCommandHandler->__invoke($this->followCommand);
    }

    public function testCannotFollowYourself(): void
    {
        $sameFollowerId = new FollowerId($this->following->getFollowerId());
        $sameFolloweeId = new FolloweeId($this->following->getFollowerId());

        $this->followerRepository->expects($this->once())
            ->method('getById')
            ->willReturn($sameFollowerId);

        $this->followeeRepository->expects($this->once())
            ->method('getById')
            ->willReturn($sameFolloweeId);

        $this->expectException(CannotFollowYourselfException::class);

        $this->followCommandHandler->__invoke($this->followCommand);
    }

    public function testAlreadyFollowedException(): void
    {
        $this->followerRepository->expects($this->once())
            ->method('getById')
            ->willReturn($this->following->getFollowerId());

        $this->followeeRepository->expects($this->once())
            ->method('getById')
            ->willReturn($this->following->getFolloweeId());

        $this->followingRepository->expects($this->once())
            ->method('hasFollowing')
            ->with($this->followCommand->followeeId, $this->followCommand->followerId)
            ->willReturn(true);

        $this->expectException(AlreadyFollowedException::class);

        $this->followCommandHandler->__invoke($this->followCommand);
    }
}
