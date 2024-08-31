<?php

namespace App\Account\Following\Infrastructure\Repository;

use App\Account\Following\Domain\Entity\Following;
use App\Account\Following\Domain\Repository\FollowingRepositoryInterface;
use App\Account\Following\Domain\ValueObject\FolloweeId;
use App\Account\Following\Domain\ValueObject\FollowerId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class PostgresFollowingRepository extends
    ServiceEntityRepository implements FollowingRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Following::class);
    }

    public function add(Following $following): void
    {
        $this->getEntityManager()->persist($following);
    }

    public function hasFollowing(
        FolloweeId $followeeId,
        FollowerId $followerId
    ): bool {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t.followerId)')
            ->where('t.followeeId = :followeeId')
            ->andWhere('t.followerId = :followerId')
            ->setParameter('followeeId', $followeeId->getValue())
            ->setParameter('followerId', $followerId->getValue())
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }
}