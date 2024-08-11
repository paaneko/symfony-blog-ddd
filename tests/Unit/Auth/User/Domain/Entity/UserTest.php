<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\User\Domain\Entity;

use App\Auth\User\Domain\Entity\User;
use App\Auth\User\Domain\ValueObject\UserEmail;
use App\Auth\User\Domain\ValueObject\UserId;
use App\Auth\User\Domain\ValueObject\UserName;
use App\Tests\Builder\Auth\User\Entity\TokenBuilder;
use App\Tests\Builder\Auth\User\Entity\UserBuilder;
use App\Tests\UnitTestCase;

final class UserTest extends UnitTestCase
{
    public function testCanRequestJoinByEmail(): void
    {
        $user = User::requestJoinByEmail(
            $userId = new UserId($this->faker->uuid()),
            $userName = new UserName($this->faker->name()),
            $userEmail = new UserEmail($this->faker->email()),
            $userPassword = 'hashedPassword',
            $joinConfirmToken = (new TokenBuilder())->build(),
            $userCreatedAt = new \DateTimeImmutable()
        );

        $this->assertEquals($userId, $user->getId());
        $this->assertEquals($userName, $user->getName());
        $this->assertEquals($userEmail, $user->getEmail());
        $this->assertEquals($userPassword, $user->getPasswordHash());
        $this->assertEquals($joinConfirmToken, $user->getJoinConfirmToken());
        $this->assertEquals($userCreatedAt, $user->getCreatedAt());
        $this->assertFalse($user->isActive());
    }

    public function testCanConfirmJoin(): void
    {
        $now = new \DateTimeImmutable();
        $token = (new TokenBuilder())->build();
        $user = (new UserBuilder())->withToken($token)->build();

        $user->confirmJoin($token->getValue(), $now);

        $this->assertTrue($user->isActive());
        $this->assertNull($user->getJoinConfirmToken());
    }
}
