<?php

namespace App\Account\Following\Infrastructure\Repository;

use App\Account\Following\Application\Repository\FolloweeRepositoryInterface;
use App\Account\Following\Application\Repository\FollowerRepositoryInterface;
use App\Account\Following\Domain\Entity\Following;
use App\Account\Following\Domain\Exception\FolloweeNotFoundException;
use App\Account\Following\Domain\ValueObject\FolloweeId;
use App\Account\Following\Domain\ValueObject\FollowerId;
use App\Auth\User\Domain\Entity\User;
use App\Auth\User\Domain\ValueObject\UserId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class PostgresFollowerRepository extends ServiceEntityRepository implements FollowerRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getById(string $id): FollowerId
    {
        /** @var User $followee */
        $followee = $this->find(new UserId($id));

        if ($followee === null) {
            throw new FolloweeNotFoundException();
        }

        return new FollowerId($followee->getId());
    }
}