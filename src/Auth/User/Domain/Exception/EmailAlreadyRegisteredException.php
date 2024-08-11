<?php

namespace App\Auth\User\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

final class EmailAlreadyRegisteredException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Email already registered');
    }

    public function getTranslationTemplate(): string
    {
        return 'error.user.email-already-registered';
    }
}