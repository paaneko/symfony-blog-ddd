<?php

declare(strict_types=1);

namespace App\Auth\User\Domain\Entity;

use App\Auth\User\Domain\Type\UserEmailType;
use App\Auth\User\Domain\Type\UserIdType;
use App\Auth\User\Domain\Type\UserNameType;
use App\Auth\User\Domain\ValueObject\UserEmail;
use App\Auth\User\Domain\ValueObject\UserId;
use App\Auth\User\Domain\ValueObject\UserName;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table('users')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: UserIdType::NAME, length: 255)]
    private UserId $id;

    #[ORM\Column(type: UserNameType::NAME, length: 255)]
    private UserName $name;

    #[ORM\Column(type: UserEmailType::NAME, length: 255)]
    private UserEmail $email;

    public function __construct(UserId $id, UserName $name, UserEmail $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getId(): UserId
    {
        return $this->id;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getName(): UserName
    {
        return $this->name;
    }

    /** @psalm-suppress PossiblyUnusedMethod */
    public function getEmail(): UserEmail
    {
        return $this->email;
    }
}
