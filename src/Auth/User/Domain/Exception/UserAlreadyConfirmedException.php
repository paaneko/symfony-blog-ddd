<?php

namespace App\Auth\User\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

final class UserAlreadyConfirmedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('User already confirmed');
    }
    public function getTranslationTemplate(): string
    {
        return 'error.user.user-already-confirmed';
    }
}