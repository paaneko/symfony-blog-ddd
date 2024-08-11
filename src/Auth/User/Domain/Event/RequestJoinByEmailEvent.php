<?php

declare(strict_types=1);

namespace App\Auth\User\Domain\Event;

use App\SharedKernel\Domain\Event\DomainEventInterface;

final readonly class RequestJoinByEmailEvent implements DomainEventInterface
{
    public function __construct(private string $email, private string $confirmationToken)
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getConfirmationToken(): string
    {
        return $this->confirmationToken;
    }
}
