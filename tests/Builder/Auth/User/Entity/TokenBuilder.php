<?php

namespace App\Tests\Builder\Auth\User\Entity;

use App\Auth\User\Domain\Entity\Token;
use Faker\Factory;

final class TokenBuilder
{
    private string $value;

    private \DateTimeImmutable $expires;

    public function __construct()
    {
        $faker = Factory::create();

        $this->value = $faker->uuid();
        $this->expires = new \DateTimeImmutable();
    }

    public function withExpires(\DateTimeImmutable $expires): self
    {
        $clone = clone $this;
        $clone->expires = $expires;
        return $clone;
    }

    public function build(): Token
    {
        return new Token($this->value, $this->expires);
    }
}