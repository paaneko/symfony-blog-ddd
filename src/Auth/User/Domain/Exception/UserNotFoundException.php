<?php

namespace App\Auth\User\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

final class UserNotFoundException extends DomainException
{
    public function __construct()
    {
        parent::__construct('User not found');
    }

    public function getTranslationTemplate(): string
    {
        return 'error.user.user-not-found';
    }
}