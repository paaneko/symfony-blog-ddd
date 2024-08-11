<?php

declare(strict_types=1);

namespace App\Auth\User\Application\Factory;

use App\Auth\User\Domain\Entity\Token;

interface TokenFactoryInterface
{
    public function generate(\DateTimeImmutable $now): Token;
}
