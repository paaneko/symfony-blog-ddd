<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\User\Application\Factory;

use App\Auth\User\Application\Factory\TokenFactory;
use App\Tests\UnitTestCase;
use Symfony\Component\Uid\Factory\UuidFactory;
use Symfony\Component\Uid\Uuid;

final class TokenFactoryTest extends UnitTestCase
{
    public function testCanGenerateWithInterval(): void
    {
        $uuidFactory = $this->createStub(UuidFactory::class);
        $uuidFactory->method('create')->willReturn(new Uuid($this->faker->uuid));
        $now = new \DateTimeImmutable();

        $tokenFactory = new TokenFactory('PT1H', $uuidFactory);

        $token = $tokenFactory->generate($now);

        $this->assertEquals($now->add(new \DateInterval('PT1H')), $token->getExpires());
    }
}
