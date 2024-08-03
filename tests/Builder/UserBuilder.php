<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Auth\User\Domain\Entity\User;
use App\Auth\User\Domain\ValueObject\UserEmail;
use App\Auth\User\Domain\ValueObject\UserId;
use App\Auth\User\Domain\ValueObject\UserName;

final class UserBuilder
{
    private UserId $id;

    private UserName $name;

    private UserEmail $email;

    public function __construct()
    {
        $this->id = UserId::generate();
        $this->name = new UserName('Lorem ipsum dolor sit amet');
        $this->email = new UserEmail('example@email.com');
    }

    public function build(): User
    {
        $user = new User(
            $this->id,
            $this->name,
            $this->email
        );

        return $user;
    }
}
