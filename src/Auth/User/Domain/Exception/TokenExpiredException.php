<?php

namespace App\Auth\User\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

final class TokenExpiredException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Token is expired');
    }
    public function getTranslationTemplate(): string
    {
        return 'error.user.token-expired';
    }
}