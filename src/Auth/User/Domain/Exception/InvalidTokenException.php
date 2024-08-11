<?php

declare(strict_types=1);

namespace App\Auth\User\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

final class InvalidTokenException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Token is invalid');
    }

    public function getTranslationTemplate(): string
    {
        return 'error.user.invalid-token';
    }
}
