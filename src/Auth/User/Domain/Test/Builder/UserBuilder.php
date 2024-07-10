<?php

declare(strict_types=1);

namespace App\Auth\User\Domain\Test\Builder;

use App\Auth\User\Domain\Entity\Id;
use App\Auth\User\Domain\Entity\User;
use App\Auth\User\Domain\ValueObject\Email;
use App\Auth\User\Domain\ValueObject\Name;

class UserBuilder
{
    private Id $id;

    private Name $name;

    private Email $email;

    public function __construct()
    {
        $this->id = Id::generate();
        $this->name = new Name('Lorem ipsum dolor sit amet');
        $this->email = new Email('example@email.com');
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
