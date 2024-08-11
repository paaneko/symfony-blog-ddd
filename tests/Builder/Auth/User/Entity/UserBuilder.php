<?php

declare(strict_types=1);

namespace App\Tests\Builder\Auth\User\Entity;

use App\Auth\User\Domain\Entity\Token;
use App\Auth\User\Domain\Entity\User;
use App\Auth\User\Domain\ValueObject\UserEmail;
use App\Auth\User\Domain\ValueObject\UserId;
use App\Auth\User\Domain\ValueObject\UserName;
use Faker\Factory;

final class UserBuilder
{
    private UserId $id;

    private UserName $name;

    private UserEmail $email;

    private string $passwordHash;

    private Token $joinConfirmToken;

    private bool $active;

    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $faker = Factory::create();

        $this->id = UserId::generate();
        $this->name = new UserName($faker->name());
        $this->email = new UserEmail($faker->email());
        $this->passwordHash = 'hashedPassword';
        $this->joinConfirmToken = (new TokenBuilder())->build();
        $this->createdAt = new \DateTimeImmutable();
        $this->active = false;
    }

    public function active(): self
    {
        $clone = clone $this;
        $clone->active = true;
        return $clone;
    }

    public function withToken(Token $token): self
    {
        $clone = clone $this;
        $clone->joinConfirmToken = $token;
        return $clone;
    }

    public function withCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $clone = clone $this;
        $clone->createdAt = $createdAt;
        return $clone;
    }

    public function withPasswordHash(string $passwordHash): self
    {
        $clone = clone $this;
        $clone->passwordHash = $passwordHash;
        return $clone;
    }

    public function build(): User
    {
        $user = User::requestJoinByEmail(
            $this->id,
            $this->name,
            $this->email,
            $this->passwordHash,
            $this->joinConfirmToken,
            $this->createdAt
        );

        if ($this->active) {
            $user->confirmJoin(
                $this->joinConfirmToken->getValue(),
                $this->joinConfirmToken->getExpires()->modify('-1 hour')
            );
        }

        return $user;
    }
}
