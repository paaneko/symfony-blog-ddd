<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\User\Domain\Entity;

use App\Auth\User\Domain\Entity\Token;
use App\Auth\User\Domain\Exception\InvalidTokenException;
use App\Auth\User\Domain\Exception\TokenExpiredException;
use App\Tests\Builder\Auth\User\Entity\TokenBuilder;
use App\Tests\UnitTestCase;

final class TokenTest extends UnitTestCase
{
    public function testCanCreate(): void
    {
        $expires = (new \DateTimeImmutable())->modify('+1 hour');
        $token = new Token('token_value', $expires);

        $this->assertEquals('token_value', $token->getValue());
        $this->assertEquals($expires, $token->getExpires());
    }

    public function testCanHandleInvalidToken(): void
    {
        $now = new \DateTimeImmutable();
        $token = (new TokenBuilder())->withExpires($now->modify('+1 hour'))->build();

        $this->expectException(InvalidTokenException::class);

        $token->validate('invalid_token', $now);
    }

    public function testCanHandleExpiredToken(): void
    {
        $now = new \DateTimeImmutable();
        $token = (new TokenBuilder())->withExpires($now->modify('-1 hour'))->build();

        $this->expectException(TokenExpiredException::class);

        $token->validate($token->getValue(), $now);
    }
}
