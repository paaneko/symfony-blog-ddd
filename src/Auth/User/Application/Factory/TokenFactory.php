<?php

declare(strict_types=1);

namespace App\Auth\User\Application\Factory;

use App\Auth\User\Domain\Entity\Token;
use Symfony\Component\Uid\Factory\UuidFactory;

final class TokenFactory implements TokenFactoryInterface
{
    public function __construct(
        private string $interval,
        private UuidFactory $uuidFactory
    ) {
    }

    public function generate(\DateTimeImmutable $now): Token
    {
        $dateInterval = new \DateInterval($this->interval);
        $value = (string) $this->uuidFactory->create();

        return new Token($value, $now->add($dateInterval));
    }
}
