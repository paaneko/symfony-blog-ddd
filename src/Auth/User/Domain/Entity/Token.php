<?php

namespace App\Auth\User\Domain\Entity;

use App\Auth\User\Domain\Exception\InvalidTokenException;
use App\Auth\User\Domain\Exception\TokenExpiredException;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class Token
{
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $value;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $expires;

    public function __construct(string $value, \DateTimeImmutable $expires)
    {
        $this->value = $value;
        $this->expires = $expires;
    }

    public function validate(string $value, \DateTimeImmutable $now): void
    {
        if ($this->value !== $value) {
            throw new InvalidTokenException();
        }

        if ($this->expires <= $now) {
            throw new TokenExpiredException();
        }
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getExpires(): ?\DateTimeImmutable
    {
        return $this->expires;
    }
}