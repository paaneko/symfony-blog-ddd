<?php

namespace App\Account\Following\Infrastructure\Repository;

use App\Account\Following\Application\Repository\FolloweeRepositoryInterface;
use App\Account\Following\Domain\Exception\FolloweeNotFoundException;
use App\Account\Following\Domain\ValueObject\FolloweeId;
use App\Auth\User\Domain\Entity\User;
use App\Auth\User\Domain\ValueObject\UserId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class PostgresFolloweeRepository extends ServiceEntityRepository implements FolloweeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getById(string $id): FolloweeId
    {
        /** @var User $followee */
        $followee = $this->find(new UserId($id));

        if ($followee === null) {
            throw new FolloweeNotFoundException();
        }

        return new FolloweeId($followee->getId());
    }
}