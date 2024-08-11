<?php

declare(strict_types=1);

namespace App\Auth\User\Infrastructure\Repository;

use App\Auth\User\Domain\Entity\User;
use App\Auth\User\Domain\Repository\UserRepositoryInterface;
use App\Auth\User\Domain\ValueObject\UserEmail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @phpstan-ignore-next-line
 *
 * @psalm-suppress UnusedClass
 */
final class PostgresUserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    /** @psalm-suppress UnusedMethod, PossiblyUnusedParam */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $user): void
    {
        $this->getEntityManager()->persist($user);
    }

    public function hasByEmail(UserEmail $email): bool
    {
        $result = $this->findOneBy(['email' => $email->getValue()]);

        return null !== $result;
    }
}
