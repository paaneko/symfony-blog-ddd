<?php

namespace App\Notification\Infrastructure\Repository;

use App\Auth\User\Domain\Entity\User;
use App\Notification\Application\Dto\NotificationUserDto;
use App\Notification\Domain\Exception\NotificationUserNotFoundException;
use App\Notification\Domain\Repository\NotificationUserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class PostgresNotificationUserRepository extends ServiceEntityRepository implements NotificationUserRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getById(string $id): NotificationUserDto
    {
        /** @var User|null $user */
        $user = $this->find($id);

        if ($user === null) {
            throw new NotificationUserNotFoundException();
        }

        return new NotificationUserDto(
            $user->getId()->getValue(),
            $user->getName()->getValue(),
            $user->getEmail()->getValue()
        );
    }
}