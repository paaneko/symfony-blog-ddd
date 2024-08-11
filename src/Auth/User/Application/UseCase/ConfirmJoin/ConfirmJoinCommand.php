<?php

namespace App\Auth\User\Application\UseCase\ConfirmJoin;

use App\SharedKernel\Domain\Bus\CommandInterface;

final readonly class ConfirmJoinCommand implements CommandInterface
{
    public string $userId;

    public string $confirmToken;

    public function __construct(string $userId, string $confirmToken)
    {
        $this->userId = $userId;
        $this->confirmToken = $confirmToken;
    }
}